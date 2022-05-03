<?php

namespace Ghostr\Cockroach\Schema;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\PostgresSchemaState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;

class CockroachSchemaState extends PostgresSchemaState
{
    protected const NEW_LINE = "\n";

    protected string $path;

    protected array $sessionVars = [];

    /** @inheritDoc */
    public function dump(Connection $connection, $path): void
    {
        $this->path = $path;

        $this->files->delete($path);

        $this->append($this->compileTypesCreate($connection));

        $this->append($this->compileSchema($connection));

        $this->files->prepend($this->compileSessionVars($connection));

        $this->files->append($this->compileMigrationsTableInsert($connection));
    }

    /**
     * Generate SQL for all user-defined data types
     */
    protected function compileTypesCreate(Connection $connection): string
    {
        return str(
            collect($connection->select("SHOW ENUMS"))
                ->map(fn($v) => str("CREATE TYPE $v->name AS ENUM (")
                    ->append(str($v->values)
                        ->substr(1, -1)
                        ->explode(',')
                        ->map(fn($v) => "'$v'")
                        ->implode(', '))
                    ->append(')')
                )
                ->implode(";\n")
        )
            ->whenNotEmpty(
                fn(Stringable $str) => $str->append(';')
            );
    }

    /**
     * Generate SQL for all tables
     */
    protected function compileSchema(Connection $connection): string
    {
        return tap(
            collect($connection->select("SHOW CREATE ALL TABLES"))
                ->pluck('create_statement')
                ->join(self::NEW_LINE),
            function (string $schema) {
                if (str_contains($schema, 'USING HASH')) {
                    $this->sessionVars['experimental_enable_hash_sharded_indexes'] = 'true';
                }
            }
        );
    }

    /**
     * Generate SQL for all necessary session variables
     */
    protected function compileSessionVars(Connection $connection): string
    {
        return implode(
            self::NEW_LINE,
            array_map(
                static fn($v, $k) => "SET $k = $v;",
                $this->sessionVars,
                array_keys($this->sessionVars)
            )
        );
    }

    /**
     * Generate an SQL insert statement with all values from the migrations table
     */
    protected function compileMigrationsTableInsert(Connection $connection): string
    {
        $migrations = $connection->table($this->migrationTable)
            ->get()
            ->map(
                fn($v) => array_map(
                    static fn($v) => new Expression("'$v'"),
                    (array)$v
                )
            )
            ->all();

        return $connection->getQueryGrammar()
            ->compileInsert($connection->table($this->migrationTable), $migrations);
    }

    protected function prepend(string $value): void
    {
        $this->files->prepend($this->path, $value . self::NEW_LINE);
    }

    protected function append(string $value): void
    {
        $this->files->append($this->path, $value . self::NEW_LINE);
        ($this->output)(null, $value . self::NEW_LINE);
    }

    public function load($path): void
    {
        $queries = $this->files->get($path);

        DB::unprepared($queries);
    }
}

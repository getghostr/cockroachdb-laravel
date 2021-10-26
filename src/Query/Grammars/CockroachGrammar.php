<?php

namespace Ghostr\Cockroach\Query\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\PostgresGrammar;

class CockroachGrammar extends PostgresGrammar
{
    /** {@inheritDoc} */
    protected function compileUpdateWithJoinsOrLimit(Builder $query, array $values)
    {
        $table = $this->wrapTable($query->from);

        $columns = $this->compileUpdateColumns($query, $values);

        $alias = last(preg_split('/\s+as\s+/i', $query->from));

        $selectSql = $this->compileSelect($query->select($alias.'.rowid'));

        return "update {$table} set {$columns} where {$this->wrap('rowid')} in ({$selectSql})";
    }

    /** {@inheritDoc} */
    protected function compileDeleteWithJoinsOrLimit(Builder $query)
    {
        $table = $this->wrapTable($query->from);

        $alias = last(preg_split('/\s+as\s+/i', $query->from));

        $selectSql = $this->compileSelect($query->select($alias.'.rowid'));

        return "delete from {$table} where {$this->wrap('rowid')} in ({$selectSql})";
    }
}

<?php

namespace Ghostr\Cockroach;

use Ghostr\Cockroach\Schema\CockroachSchemaState;
use Illuminate\Database\PostgresConnection;
use Ghostr\Cockroach\Query\Grammars\CockroachGrammar as QueryGrammar;
use Ghostr\Cockroach\Schema\Grammars\CockroachGrammar as SchemaGrammar;
use Illuminate\Filesystem\Filesystem;

class CockroachConnection extends PostgresConnection
{
    /**
     * Get the default query grammar instance.
     *
     * @return QueryGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the schema state for the connection.
     *
     * @return CockroachSchemaState
     */
    public function getSchemaState(Filesystem $files = null, callable $processFactory = null)
    {
        return new CockroachSchemaState($this, $files, $processFactory);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return SchemaGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }
}

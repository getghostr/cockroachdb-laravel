<?php

namespace Ghostr\Cockroach;

use Illuminate\Database\PostgresConnection;
use Ghostr\Cockroach\Query\Grammars\CockroachGrammar as QueryGrammar;
use Ghostr\Cockroach\Schema\Grammars\CockroachGrammar as SchemaGrammar;

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
     * Get the default schema grammar instance.
     *
     * @return SchemaGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }
}

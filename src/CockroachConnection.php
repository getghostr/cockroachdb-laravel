<?php

namespace Ghostr\Cockroach;

use Illuminate\Database\PostgresConnection;
use Ghostr\Cockroach\Query\Grammars\CockroachGrammar as QueryGrammar;
use Ghostr\Cockroach\Schema\Grammars\CockroachGrammar as SchemaGrammar;

class CockroachConnection extends PostgresConnection
{
    /** {@inheritDoc} */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /** {@inheritDoc} */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }
}

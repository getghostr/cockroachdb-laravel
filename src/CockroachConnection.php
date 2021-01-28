<?php

namespace Ghostr\Cockroach;

use Illuminate\Database\PostgresConnection;
use Ghostr\Cockroach\Query\Grammars\CockroachGrammar as QueryGrammar;

class CockroachConnection extends PostgresConnection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Ghostr\Cockroach\Query\Grammars\CockroachGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }
}

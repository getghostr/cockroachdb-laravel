<?php

namespace Ghostr\Cockroach\Schema\Grammars;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class CockroachGrammar extends PostgresGrammar
{
    /** {@inheritDoc} */
    public function compileIndex(Blueprint $blueprint, Fluent $command)
    {
        return sprintf('create index %s on %s%s (%s)%s',
            $this->wrap($command->index),
            $this->wrapTable($blueprint),
            $command->algorithm ? ' using ' . $command->algorithm : '',
            $this->columnize($command->columns),
            !empty($command->storing) ? ' storing (' . $this->columnize(Arr::wrap($command->storing)) . ')' : ''
        );
    }
}

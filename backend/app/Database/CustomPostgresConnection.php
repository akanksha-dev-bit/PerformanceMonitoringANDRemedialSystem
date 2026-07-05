<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection;
use DateTimeInterface;

class CustomPostgresConnection extends PostgresConnection
{
    /**
     * Prepare the query bindings for execution.
     *
     * @param  array  $bindings
     * @return array
     */
    public function prepareBindings(array $bindings)
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                // Return 'true' or 'false' string so that PDO emulated prepared
                // statements compile them as SQL boolean literals instead of 1/0 integers.
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }

        return $bindings;
    }
}

<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Processors;

class NullStringProcessor
{
    /**
     * Transform a "NULL" string record into a null value.
     *
     * @param  array  $record
     * @return array
     */
    public function __invoke(array $record): array
    {
        foreach ($record['context'] as $key => $value) {
            if (is_string($value) && strtoupper($value) === 'NULL') {
                $record['context'][$key] = null;
            }
        }

        return $record;
    }
}

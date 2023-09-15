<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Processors;

use Monolog\LogRecord;

class NullStringProcessor
{
    /**
     * Transform a "NULL" string record into a null value.
     *
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $record->context;

        foreach ($context as $key => $value) {
            if (is_string($value) && strtoupper($value) === 'NULL') {
                $context[$key] = null;
            }
        }

        return $record->with(context: $context);
    }
}

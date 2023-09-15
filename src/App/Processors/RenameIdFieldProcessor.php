<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Processors;

use Monolog\LogRecord;

class RenameIdFieldProcessor
{
    /**
     * Rename "id" field  to "_id" (additional field 'id' is not allowed).
     *
     * @see https://github.com/hedii/laravel-gelf-logger/issues/33
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $record->context;

        foreach ($context as $key => $value) {
            if ($key === 'id' && !array_key_exists('_id', $record['context'])) {
                unset($context['id']);

                $context['_id'] = $value;
            }
        }

        return $record->with(context: $context);
    }
}

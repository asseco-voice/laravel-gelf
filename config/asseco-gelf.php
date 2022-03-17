<?php

use Asseco\Gelf\App\GelfLoggerFactory;

return [
    'gelf' => [
        'driver' => 'custom',
        'via'    => GelfLoggerFactory::class,
    ]
];
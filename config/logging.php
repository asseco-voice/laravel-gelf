<?php

use Asseco\Gelf\App\Events\LoggingEvent;
use Asseco\Gelf\App\GelfLoggerFactory;
use Asseco\Gelf\App\Processors\NullStringProcessor;
use Asseco\Gelf\App\Processors\RenameIdFieldProcessor;

return [
    'driver'         => 'custom',
    'via'            => GelfLoggerFactory::class,
    'level'          => 'debug',

    /**
     * Processors that should be pushed to the handler.
     * This option is useful to modify a field
     * in the log context (see NullStringProcessor), or to add extra
     * data. Each processor must be a callable or an object with an
     * __invoke method: see monolog documentation about processors.
     */
    'processors'     => [
        NullStringProcessor::class,
        RenameIdFieldProcessor::class,
        // ...
    ],

    /**
     * Channel over which the messages are transported.
     * Available options: udp, tcp, http or event
     */
    'transport'      => env('GELF_TRANSPORT', 'event'),

    /**
     * Event transport specific options
     */
    'event'          => [
        /**
         * Event to be triggered. If you want to override this behavior, be sure your event
         * implements Asseco\Stomp\Queue\Contracts\HasRawData interface.
         */
        'class' => LoggingEvent::class,

        /**
         * Queue where the event should be dispatched
         */
        'queue' => 'gelf_logs',
    ],

    /**
     * This option determines the host that will receive the
     * gelf log messages.
     */
    'host'           => env('GELF_HOST', 'graylog'),

    /**
     * This option determines the port on which the gelf
     * receiver host is listening.
     */
    'port'           => env('GELF_PORT', 12201),

    /**
     * This option determines the path used for the HTTP
     * transport. When forgotten or set to null, default path '/gelf'
     * is used.
     */
    'path'           => null,

    /**
     * This option determines the system name sent with the
     * message in the 'source' field. When forgotten or set to null,
     * the current hostname is used.
     */
    'system_name'    => null,

    /**
     * This option determines the prefix for 'extra' fields
     * from the Monolog record.
     */
    'extra_prefix'   => null,

    /**
     * This option determines the prefix for 'context' fields
     * from the Monolog record.
     */
    'context_prefix' => null,

    /**
     * This option determines the maximum length per message
     * field. When forgotten or set to null, the default value of
     * \Monolog\Formatter\GelfMessageFormatter::DEFAULT_MAX_LENGTH is
     * used (currently this value is 32766)
     */
    'max_length'     => null,

    /**
     * This option enables or disable ssl on TCP or HTTP
     * transports.
     */
    'ssl'            => false,

    // If ssl is enabled, the following configuration is used.
    'ssl_options'    => [
        // Enable or disable the peer certificate check.
        'verify_peer'       => true,

        // Path to a custom CA file (eg: "/path/to/ca.pem").
        'ca_file'           => null,

        // List of ciphers the SSL layer may use, formatted as
        // specified in ciphers(1).
        'ciphers'           => null,

        // Whether self-signed certificates are allowed.
        'allow_self_signed' => false,
    ],
];
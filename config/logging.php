<?php

use Asseco\Gelf\App\Processors\NullStringProcessor;
use Asseco\Gelf\App\Processors\RenameIdFieldProcessor;

return [

    /**
     * This option determines the processors that should be
     * pushed to the handler. This option is useful to modify a field
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
     * This option determines the minimum "level" a message
     * must be in order to be logged by the channel.
     */
    'level'          => 'debug',

    /**
     * This option determines the channel name sent with the
     * message in the 'facility' field.
     */
    'name'           => 'my-custom-name',

    /**
     * This option determines the system name sent with the
     * message in the 'source' field. When forgotten or set to null,
     * the current hostname is used.
     */
    'system_name'    => null,

    /**
     * This option determines if you want the UDP, TCP, HTTP or event
     * transport for the gelf log messages.
     */
    'transport'      => 'event',

    /**
     * This option determines the host that will receive the
     * gelf log messages. Default is 127.0.0.1
     */
    'host'           => '127.0.0.1',

    /**
     * This option determines the port on which the gelf
     * receiver host is listening. Default is 12201
     */
    'port'           => 12201,

    /**
     * This option determines the path used for the HTTP
     * transport. When forgotten or set to null, default path '/gelf'
     * is used.
     */
    'path'           => null,

    /**
     * This option enable or disable ssl on TCP or HTTP
     * transports. Default is false.
     */
    'ssl'            => false,

    // If ssl is enabled, the following configuration is used.
    'ssl_options'    => [
        // Enable or disable the peer certificate check. Default is
        // true.
        'verify_peer'       => true,

        // Path to a custom CA file (eg: "/path/to/ca.pem"). Default
        // is null.
        'ca_file'           => null,

        // List of ciphers the SSL layer may use, formatted as
        // specified in ciphers(1). Default is null.
        'ciphers'           => null,

        // Whether self-signed certificates are allowed. Default is
        // false.
        'allow_self_signed' => false,
    ],

    /**
     * This option determines the maximum length per message
     * field. When forgotten or set to null, the default value of
     * \Monolog\Formatter\GelfMessageFormatter::DEFAULT_MAX_LENGTH is
     * used (currently this value is 32766)
     */
    'max_length'     => null,

    /**
     * This option determines the prefix for 'context' fields
     * from the Monolog record. Default is null (no context prefix)
     */
    'context_prefix' => null,

    /**
     * This option determines the prefix for 'extra' fields
     * from the Monolog record. Default is null (no extra prefix)
     */
    'extra_prefix'   => null,
];
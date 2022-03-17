<?php

declare(strict_types=1);

namespace Asseco\Gelf\App;

use Asseco\Gelf\App\Transports\EventTransport;
use Gelf\Publisher;
use Gelf\Transport\AbstractTransport;
use Gelf\Transport\HttpTransport;
use Gelf\Transport\IgnoreErrorTransportWrapper;
use Gelf\Transport\SslOptions;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\GelfHandler;
use Monolog\Logger;

class GelfLoggerFactory
{
    protected Container $app;

    protected array $logLevels = [
        'debug'     => Logger::DEBUG,
        'info'      => Logger::INFO,
        'notice'    => Logger::NOTICE,
        'warning'   => Logger::WARNING,
        'error'     => Logger::ERROR,
        'critical'  => Logger::CRITICAL,
        'alert'     => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function __invoke(array $config): Logger
    {
        $transport = new IgnoreErrorTransportWrapper(
            $this->getTransport(
                $config['transport'] ?? 'udp',
                $config['host'] ?? '127.0.0.1',
                $config['port'] ?? 12201,
                $config['path'] ?? null,
                $this->enableSsl($config) ? $this->sslOptions($config['ssl_options'] ?? null) : null
            )
        );

        $handler = new GelfHandler(new Publisher($transport), $this->level($config));

        $handler->setFormatter(
            new GelfMessageFormatter(
                $config['system_name'] ?? null,
                $config['extra_prefix'] ?? null,
                $config['context_prefix'] ?? '',
                $config['max_length'] ?? null
            )
        );

        foreach ($this->parseProcessors($config) as $processor) {
            $handler->pushProcessor(new $processor);
        }

        return new Logger($this->parseChannel($config), [$handler]);
    }

    /**
     * Get the transport class based on the
     * config value.
     *
     * @param string $transport
     * @param string $host
     * @param int $port
     * @param string|null $path
     * @param SslOptions|null $sslOptions
     * @return AbstractTransport
     */
    protected function getTransport(string $transport, string $host, int $port, ?string $path = null, ?SslOptions $sslOptions = null): AbstractTransport
    {
        switch (strtolower($transport)) {
            case 'tcp':
                return new TcpTransport($host, $port, $sslOptions);

            case 'http':
                return new HttpTransport($host, $port, $path ?? HttpTransport::DEFAULT_PATH, $sslOptions);

            case 'event':
                return new EventTransport();

            default:
                return new UdpTransport($host, $port);
        }
    }

    protected function enableSsl(array $config): bool
    {
        if (!isset($config['transport']) || $config['transport'] === 'udp') {
            return false;
        }

        return $config['ssl'] ?? false;
    }

    protected function sslOptions(?array $sslConfig = null): SslOptions
    {
        $sslOptions = new SslOptions();

        if (!$sslConfig) {
            return $sslOptions;
        }

        $sslOptions->setVerifyPeer($sslConfig['verify_peer'] ?? true);
        $sslOptions->setCaFile($sslConfig['ca_file'] ?? null);
        $sslOptions->setCiphers($sslConfig['ciphers'] ?? null);
        $sslOptions->setAllowSelfSigned($sslConfig['allow_self_signed'] ?? false);

        return $sslOptions;
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     * @return int
     * @throws InvalidArgumentException
     */
    protected function level(array $config): int
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->logLevels[$level])) {
            return $this->logLevels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }

    /**
     * Extract the processors from the given configuration.
     *
     * @param array $config
     * @return array
     */
    protected function parseProcessors(array $config): array
    {
        $processors = [];

        if (isset($config['processors']) && is_array($config['processors'])) {
            foreach ($config['processors'] as $processor) {
                $processors[] = $processor;
            }
        }

        return $processors;
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     * @return string
     */
    protected function parseChannel(array $config): string
    {
        if (!isset($config['name'])) {
            return $this->getFallbackChannelName();
        }

        return $config['name'];
    }

    /**
     * Get fallback log channel name.
     *
     * @return string
     */
    protected function getFallbackChannelName(): string
    {
        return $this->app->bound('env') ? $this->app->environment() : 'production';
    }
}

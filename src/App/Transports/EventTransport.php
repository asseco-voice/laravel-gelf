<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Transports;

use Asseco\Gelf\App\Events\LoggingEvent;
use Gelf\MessageInterface as Message;
use Gelf\Transport\AbstractTransport;

class EventTransport extends AbstractTransport
{
    public function send(Message $message)
    {
        LoggingEvent::dispatch($message);
    }
}
<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Transports;

use Gelf\MessageInterface as Message;
use Gelf\Transport\AbstractTransport;

class EventTransport extends AbstractTransport
{
    public function send(Message $message)
    {
        $event = config('logging.channels.gelf.event.class');
        $event::dispatch($message);

        return 1;
    }
}

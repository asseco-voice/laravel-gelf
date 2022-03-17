<?php

declare(strict_types=1);

namespace Asseco\Gelf\App\Events;

use Asseco\Stomp\Queue\Contracts\HasRawData;
use Gelf\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class LoggingEvent implements ShouldBroadcast, HasRawData
{
    use Dispatchable, InteractsWithSockets;

    public Message $logMessage;

    public function __construct(Message $message)
    {
        $this->logMessage = $message;
    }

    public function getRawData(): array
    {
        return $this->logMessage->toArray();
    }

    public function broadcastQueue()
    {
        return config('logging.channels.gelf.event.queue');
    }

    public function broadcastOn()
    {
        return [];
    }
}

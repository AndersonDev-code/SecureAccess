<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestWebSocket implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message envoyé au navigateur.
     */
    public string $message;

    /**
     * Création de l'événement.
     */
    public function __construct()
    {
        $this->message = 'WebSocket SecureAccess fonctionne !';
    }

    /**
     * Canal sur lequel l'événement sera diffusé.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('secureaccess-test'),
        ];
    }

    /**
     * Nom de l'événement reçu par JavaScript.
     */
    public function broadcastAs(): string
    {
        return 'test.websocket';
    }
}
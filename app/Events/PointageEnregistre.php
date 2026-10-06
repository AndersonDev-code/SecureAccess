<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PointageEnregistre implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $pointage;

    public function __construct(array $pointage)
    {
        $this->pointage = $pointage;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('secureaccess-pointages'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'pointage.enregistre';
    }

    /**
     * Données envoyées explicitement au navigateur.
     */
    public function broadcastWith(): array
    {
        return [
            'pointage' => $this->pointage,
        ];
    }
}
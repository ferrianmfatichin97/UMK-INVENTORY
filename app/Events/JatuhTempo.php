<?php

namespace App\Events;

use App\Models\Data_kendaraan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class JatuhTempo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kendaraan;
    public $dueDate;

    public function __construct(Data_kendaraan $kendaraan, Carbon $dueDate)
    {
        $this->kendaraan = $kendaraan;
        $this->dueDate = $dueDate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

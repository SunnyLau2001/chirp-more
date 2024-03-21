<?php

namespace App\Events;

use App\Models\Chirp;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChirpCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Chirp $chirp)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chirp-preview'),
        ];
    }

    public function broadcastWith(): array
    {
        $chirp_with_relation = Chirp::with(['user:id,name', 'likes.user:id,name'])->find($this->chirp->id, ['id']);

        return [
            'chirp' => $chirp_with_relation
        ];
    }
}

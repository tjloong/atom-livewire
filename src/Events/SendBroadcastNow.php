<?php

namespace Jiannius\Atom\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendBroadcastNow implements ShouldBroadcastNow
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public $config)
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
        $private = collect(get($this->config, 'private'))->map(fn ($channel) => new PrivateChannel($channel));
        $public = collect(get($this->config, 'public'))->map(fn ($channel) => new Channel($channel));

        return $private->concat($public)->values()->all();
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return get($this->config, 'name');
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     */
    public function broadcastQueue(): string
    {
        return get($this->config, 'queue');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return get($this->config, 'with');
    }
}

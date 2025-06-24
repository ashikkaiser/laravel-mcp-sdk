<?php

namespace LaravelMCP\MCP\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * MCP private message broadcasting event.
 *
 * This event is used to broadcast MCP messages to specific clients
 * through Laravel's broadcasting system using private channels.
 */
class MCPPrivateMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string The private channel name
     */
    public string $channelName;

    /**
     * @var array The message data
     */
    public array $message;

    /**
     * Create a new event instance.
     *
     * @param string $channelName The private channel to broadcast on
     * @param array $message The message data to broadcast
     */
    public function __construct(string $channelName, array $message)
    {
        $this->channelName = $channelName;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->channelName),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return $this->message;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'mcp.private.message';
    }
}

<?php

namespace LaravelMCP\MCP\Transport;

use LaravelMCP\MCP\Contracts\MCPServerInterface;
use LaravelMCP\MCP\Contracts\TransportInterface;
use React\EventLoop\LoopInterface;
use RuntimeException;

/**
 * Factory for creating transport instances in the MCP system.
 *
 * This factory is responsible for creating and configuring transport
 * implementations based on the requested type. It supports multiple
 * transport protocols and handles their initialization with the
 * appropriate event loop.
 *
 * Supported transports:
 * - HTTP
 * - WebSocket (Laravel Reverb)
 * - Standard I/O
 *
 * @package LaravelMCP\MCP\Transport
 */
class TransportFactory
{
    /**
     * Create a new transport instance.
     *
     * Creates and configures a transport implementation based on the
     * specified type. The transport will be initialized with the
     * provided server, event loop, and configuration.
     *
     * @param string $type The type of transport to create ('http', 'websocket', 'stdio')
     * @param MCPServerInterface $server The MCP server instance
     * @param LoopInterface $loop The event loop to use
     * @param array $config Optional configuration parameters
     * @return TransportInterface The configured transport instance
     * @throws RuntimeException If the transport type is not supported
     */
    public function create(string $type, MCPServerInterface $server, LoopInterface $loop, array $config = []): TransportInterface
    {
        return match ($type) {
            'http' => new HttpTransport($server, $config),
            'websocket', 'reverb' => new WebSocketTransport($config),
            'stdio' => new StdioTransport($server),
            default => throw new RuntimeException("Unsupported transport type: {$type}"),
        };
    }
}

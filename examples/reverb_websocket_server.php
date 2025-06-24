<?php

/**
 * Laravel Reverb WebSocket Transport Example
 *
 * This example demonstrates how to use the MCP WebSocket transport
 * with Laravel Reverb for real-time communication.
 *
 * Requirements:
 * - Laravel 11+ with Reverb
 * - composer require laravel/reverb
 * - php artisan reverb:install
 */

use LaravelMCP\MCP\Transport\WebSocketTransport;
use LaravelMCP\MCP\Server\FastMCP;

// Load Laravel application
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Laravel MCP WebSocket Server with Reverb\n";
echo "========================================\n\n";

try {
    // Configuration for the WebSocket transport
    $config = [
        'host' => '127.0.0.1',
        'port' => 8080,
        'app_id' => 'mcp-app',
        'app_key' => 'mcp-key',
        'app_secret' => 'mcp-secret',
        'channel' => 'mcp-server',
    ];

    // Create the WebSocket transport
    $transport = new WebSocketTransport($config);

    // Check if Reverb is available
    if (!$transport->isReverbAvailable()) {
        echo "❌ Laravel Reverb is not available!\n";
        echo "Please install it with:\n";
        echo "  composer require laravel/reverb\n";
        echo "  php artisan reverb:install\n";
        exit(1);
    }

    echo "✅ Laravel Reverb is available\n";
    echo "Configuration:\n";
    echo "  Host: {$config['host']}\n";
    echo "  Port: {$config['port']}\n";
    echo "  Channel: {$config['channel']}\n";
    echo "  WebSocket URL: ws://{$config['host']}:{$config['port']}/app/{$config['app_key']}\n\n";

    // Set up message handling
    $transport->setMessageHandler(function (array $message) {
        echo "📨 Received message: " . json_encode($message) . "\n";

        // Echo the message back with a timestamp
        return [
            'type' => 'response',
            'original' => $message,
            'timestamp' => now()->toISOString(),
            'server' => 'MCP Laravel Reverb',
        ];
    });

    // Start the transport
    echo "🚀 Starting WebSocket transport...\n";
    $transport->start();

    echo "✅ WebSocket server is running!\n";
    echo "📡 Broadcasting on channel: {$config['channel']}\n";
    echo "🔗 Connect using: ws://{$config['host']}:{$config['port']}/app/{$config['app_key']}\n\n";

    // Send a test message every 10 seconds
    $messageCount = 0;
    while ($transport->isRunning()) {
        sleep(10);

        $messageCount++;
        $testMessage = [
            'type' => 'heartbeat',
            'message' => "Server heartbeat #{$messageCount}",
            'timestamp' => now()->toISOString(),
            'connections' => $transport->getConnectionCount(),
        ];

        echo "💓 Sending heartbeat message...\n";
        $transport->send($testMessage);
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

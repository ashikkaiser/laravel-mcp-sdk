<?php

namespace LaravelMCP\MCP\Commands;

use Illuminate\Console\Command;
use LaravelMCP\MCP\Transport\WebSocketTransport;

/**
 * Artisan command for managing the MCP Reverb WebSocket server.
 *
 * This command provides a CLI interface for starting and managing
 * the MCP server using Laravel Reverb WebSocket transport.
 *
 * Usage:
 * ```bash
 * # Start the MCP WebSocket server with Reverb
 * php artisan mcp:reverb
 *
 * # Start with custom host and port
 * php artisan mcp:reverb --host=0.0.0.0 --port=8080
 * ```
 *
 * @package LaravelMCP\MCP\Commands
 */
class MCPReverbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcp:reverb
                            {--host=127.0.0.1 : The host address to bind to}
                            {--port=8080 : The port number to listen on}
                            {--app-id=app : The Reverb application ID}
                            {--app-key=key : The Reverb application key}
                            {--app-secret=secret : The Reverb application secret}
                            {--channel=mcp-server : The default MCP channel name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the MCP server using Laravel Reverb WebSocket transport';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Starting MCP server with Laravel Reverb WebSocket transport...');

        try {
            // Get command options
            $config = [
                'host' => $this->option('host'),
                'port' => (int) $this->option('port'),
                'app_id' => $this->option('app-id'),
                'app_key' => $this->option('app-key'),
                'app_secret' => $this->option('app-secret'),
                'channel' => $this->option('channel'),
            ];

            // Create WebSocket transport
            $transport = new WebSocketTransport($config);

            // Check if Reverb is available
            if (!$transport->isReverbAvailable()) {
                $this->error('Laravel Reverb is not available. Please install it first:');
                $this->line('  composer require laravel/reverb');
                $this->line('  php artisan reverb:install');
                return 1;
            }

            // Display configuration
            $this->displayConfiguration($config);

            // Start the transport
            $transport->start();

            $this->info('MCP WebSocket server is running. Press Ctrl+C to stop.');

            // Set up signal handlers for graceful shutdown
            if (extension_loaded('pcntl')) {
                pcntl_signal(SIGINT, function () use ($transport) {
                    $this->info('Shutting down MCP server...');
                    $transport->stop();
                    exit(0);
                });

                pcntl_signal(SIGTERM, function () use ($transport) {
                    $this->info('Shutting down MCP server...');
                    $transport->stop();
                    exit(0);
                });
            }

            // Keep the process running
            while ($transport->isRunning()) {
                if (extension_loaded('pcntl')) {
                    pcntl_signal_dispatch();
                }
                sleep(1);
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to start MCP server: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Display the server configuration.
     *
     * @param array $config The configuration array
     */
    private function displayConfiguration(array $config): void
    {
        $this->info('Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Host', $config['host']],
                ['Port', $config['port']],
                ['Channel', $config['channel']],
                ['App ID', $config['app_id']],
                ['App Key', $config['app_key']],
                ['Transport', 'Laravel Reverb WebSocket'],
            ]
        );

        $this->line('');
        $this->info('WebSocket URL: ws://' . $config['host'] . ':' . $config['port'] . '/app/' . $config['app_key']);
        $this->info('Channel: ' . $config['channel']);
        $this->line('');
    }
}

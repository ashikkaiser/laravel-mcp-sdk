# WebSocket Transport for Laravel MCP SDK

## Overview

The WebSocket transport provides real-time, bidirectional communication between MCP clients and servers. Due to dependency compatibility issues with Laravel 12, the WebSocket functionality is now **optional** and requires additional setup.

## Laravel 12 Compatibility Issue

Laravel 12 uses Symfony 7.x components, while the popular `cboden/ratchet` WebSocket library is still limited to Symfony 6.x. This creates a dependency conflict that prevents both packages from being installed together.

## Available Solutions

### 1. Laravel Reverb (Recommended for Laravel 11+)

Laravel Reverb is the official WebSocket solution for modern Laravel applications:

```bash
composer require laravel/reverb
```

**Pros:**
- Official Laravel package
- Full Laravel 12 compatibility
- Integrated with Laravel Broadcasting
- Excellent performance
- Easy configuration

**Cons:**
- Only available for Laravel 11+
- Requires configuration for MCP integration

### 2. ReactPHP WebSocket

A lightweight WebSocket solution using ReactPHP:

```bash
composer require react/socket react/http react/stream
```

**Pros:**
- Laravel 12 compatible
- Lightweight and fast
- Good documentation
- Active maintenance

**Cons:**
- Requires custom integration code
- Less Laravel-specific features

### 3. Legacy Ratchet (Laravel 10.x only)

The original WebSocket solution (only for Laravel 10.x):

```bash
# Only works with Laravel 10.x
composer require cboden/ratchet ratchet/pawl
```

**Pros:**
- Mature and stable
- Extensive documentation
- Built-in MCP integration

**Cons:**
- ❌ **Not compatible with Laravel 12**
- Limited to Laravel 10.x and older

## Implementation Examples

### Using Laravel Reverb

```php
// In your MCP server configuration
return [
    'transport' => 'reverb',
    'reverb' => [
        'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
        'port' => env('REVERB_SERVER_PORT', 8080),
        'hostname' => env('REVERB_HOST'),
        'options' => [
            'tls' => [],
        ],
        'max_request_size' => 10000,
        'scaling' => [
            'enabled' => false,
            'channel' => 'reverb',
            'server' => [
                'url' => env('REDIS_URL'),
                'host' => env('REDIS_HOST', '127.0.0.1'),
                'port' => env('REDIS_PORT', '6379'),
            ],
        ],
        'pulse' => [
            'enabled' => env('REVERB_PULSE_ENABLED', false),
            'interval' => 5,
        ],
    ],
];
```

### Using ReactPHP WebSocket

```php
use React\EventLoop\Loop;
use React\Socket\SocketServer;
use React\Http\HttpServer;
use React\Http\Message\Response;

$loop = Loop::get();
$socket = new SocketServer('127.0.0.1:8080', [], $loop);

$server = new HttpServer($loop, function (ServerRequestInterface $request) {
    // Handle WebSocket upgrade and MCP messages
    return new Response(200, ['Content-Type' => 'text/plain'], 'MCP Server');
});

$server->listen($socket);
$loop->run();
```

## Migration Guide

### From Ratchet to Laravel Reverb

1. **Remove Ratchet dependencies:**
   ```bash
   composer remove cboden/ratchet ratchet/pawl
   ```

2. **Install Laravel Reverb:**
   ```bash
   composer require laravel/reverb
   php artisan reverb:install
   ```

3. **Update your MCP transport configuration:**
   ```php
   // config/mcp.php
   'transport' => 'reverb',
   ```

4. **Update your WebSocket client code:**
   ```javascript
   // Use Reverb's WebSocket endpoint
   const socket = new WebSocket('ws://localhost:8080/app/your-app-key');
   ```

### From Ratchet to ReactPHP

1. **Remove Ratchet dependencies:**
   ```bash
   composer remove cboden/ratchet ratchet/pawl
   ```

2. **Install ReactPHP:**
   ```bash
   composer require react/socket react/http
   ```

3. **Implement custom WebSocket handler** (see example above)

## Configuration

Update your `config/mcp.php` file:

```php
return [
    'transport' => env('MCP_TRANSPORT', 'http'), // Default to HTTP

    'transports' => [
        'http' => [
            'host' => env('MCP_HTTP_HOST', '127.0.0.1'),
            'port' => env('MCP_HTTP_PORT', 8080),
        ],
        'websocket' => [
            'driver' => env('MCP_WEBSOCKET_DRIVER', 'reverb'), // reverb, reactphp, or ratchet
            'host' => env('MCP_WEBSOCKET_HOST', '127.0.0.1'),
            'port' => env('MCP_WEBSOCKET_PORT', 8081),
        ],
    ],
];
```

## Testing WebSocket Connectivity

Test your WebSocket implementation:

```bash
# Test with wscat (install with: npm install -g wscat)
wscat -c ws://localhost:8080

# Send a test MCP message
{"jsonrpc": "2.0", "method": "initialize", "id": 1}
```

## Performance Considerations

- **Laravel Reverb**: Best for Laravel-integrated applications
- **ReactPHP**: Good for high-performance, standalone servers
- **Ratchet**: Legacy option, avoid for new Laravel 12 projects

## Troubleshooting

### Common Issues

1. **Port conflicts**: Ensure your WebSocket port doesn't conflict with other services
2. **Firewall**: Make sure the WebSocket port is open
3. **SSL/TLS**: Configure proper certificates for production environments

### Debug Mode

Enable debug logging in your MCP configuration:

```php
'logging' => [
    'enabled' => true,
    'level' => 'debug',
    'channels' => ['websocket'],
],
```

## Conclusion

While the transition away from Ratchet requires some setup, the available alternatives provide better long-term compatibility with Laravel 12 and beyond. Laravel Reverb is the recommended choice for most Laravel applications.

<?php

/**
 * Laravel Reverb WebSocket Client Example
 *
 * This example demonstrates how to connect to the MCP WebSocket server
 * using Laravel Reverb.
 *
 * Requirements:
 * - Laravel 11+ with Reverb
 * - composer require laravel/reverb
 * - JavaScript WebSocket client (see HTML example below)
 */

echo "Laravel MCP WebSocket Client with Reverb\n";
echo "========================================\n\n";

echo "To connect to the MCP WebSocket server, you can use JavaScript:\n\n";

$jsExample = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <title>MCP WebSocket Client</title>
</head>
<body>
    <h1>MCP WebSocket Client</h1>
    <div id="status">Connecting...</div>
    <div id="messages"></div>
    <input type="text" id="messageInput" placeholder="Enter message...">
    <button onclick="sendMessage()">Send</button>

    <script>
        const ws = new WebSocket('ws://127.0.0.1:8080/app/mcp-key');
        const status = document.getElementById('status');
        const messages = document.getElementById('messages');

        ws.onopen = function(event) {
            status.textContent = 'Connected';
            status.style.color = 'green';

            // Subscribe to the MCP channel
            ws.send(JSON.stringify({
                event: 'pusher:subscribe',
                data: {
                    channel: 'mcp-server'
                }
            }));
        };

        ws.onmessage = function(event) {
            const data = JSON.parse(event.data);
            const messageDiv = document.createElement('div');
            messageDiv.textContent = new Date().toLocaleTimeString() + ': ' + JSON.stringify(data);
            messages.appendChild(messageDiv);
        };

        ws.onclose = function(event) {
            status.textContent = 'Disconnected';
            status.style.color = 'red';
        };

        ws.onerror = function(error) {
            status.textContent = 'Error: ' + error;
            status.style.color = 'red';
        };

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = {
                event: 'client-mcp-message',
                channel: 'mcp-server',
                data: {
                    type: 'user_message',
                    content: input.value,
                    timestamp: new Date().toISOString()
                }
            };

            ws.send(JSON.stringify(message));
            input.value = '';
        }

        // Send message on Enter key
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>
HTML;

echo $jsExample;

echo "\n\nSave the above HTML to a file and open it in your browser to test the WebSocket connection.\n\n";

echo "You can also test with wscat (install with: npm install -g wscat):\n";
echo "  wscat -c ws://127.0.0.1:8080/app/mcp-key\n\n";

echo "Example messages to send:\n";
echo "1. Subscribe to channel:\n";
echo '  {"event":"pusher:subscribe","data":{"channel":"mcp-server"}}' . "\n\n";

echo "2. Send MCP message:\n";
echo '  {"event":"client-mcp-message","channel":"mcp-server","data":{"type":"tool_call","name":"test","arguments":{}}}' . "\n\n";

echo "3. Send custom message:\n";
echo '  {"event":"client-message","channel":"mcp-server","data":{"message":"Hello from client!"}}' . "\n\n";

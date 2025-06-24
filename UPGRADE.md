# Laravel 12 Upgrade Guide

This guide will help you upgrade your Laravel MCP SDK installation to be compatible with Laravel 12.

## Prerequisites

Before upgrading, ensure you have:
- PHP 8.2 or higher
- Composer 2.x
- Laravel 10.x, 11.x, or 12.x

## Important Notice: WebSocket Transport

Due to Symfony dependency conflicts between Laravel 12 and Ratchet WebSocket library, the WebSocket transport is now **optional**.

### WebSocket Options for Laravel 12:

1. **Laravel Reverb (Recommended for Laravel 11+)**:
   ```bash
   composer require laravel/reverb
   ```

2. **ReactPHP WebSocket (Alternative)**:
   ```bash
   composer require react/socket react/http
   ```

3. **Legacy Ratchet (Laravel 10.x only)**:
   ```bash
   composer require cboden/ratchet ratchet/pawl
   ```

## Step-by-Step Upgrade

### 1. Update PHP Version

Ensure your system is running PHP 8.2 or higher:

```bash
php --version
```

If you need to upgrade PHP, follow the official PHP upgrade guide for your operating system.

### 2. Update Composer Dependencies

Update your `composer.json` to use the latest version:

```json
{
    "require": {
        "laravelmcp/mcp": "^2.0"
    }
}
```

Then run:

```bash
composer update laravelmcp/mcp
```

### 3. Clear Cache

Clear your application cache to ensure all changes take effect:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4. Test Your Installation

Verify the installation works correctly:

```bash
php artisan mcp:serve --help
```

## What's Changed

### PHP Requirements
- **Minimum PHP version**: Now requires PHP 8.2+
- **Dropped support**: PHP 8.1 is no longer supported

### Laravel Support
- **Added support**: Laravel 12.x
- **Continued support**: Laravel 10.x and 11.x
- **Dropped support**: Laravel 9.x (end of life)

### Dependencies
- Updated Orchestra Testbench for better testing support
- Updated PHPUnit to latest versions
- All ReactPHP dependencies remain compatible

## Breaking Changes

### PHP 8.1 Support Dropped
If you're currently using PHP 8.1, you'll need to upgrade to PHP 8.2 or higher.

### No API Changes
The MCP SDK API remains unchanged. All existing code should continue to work without modifications.

## Troubleshooting

### Composer Conflicts
If you encounter dependency conflicts during upgrade:

```bash
composer update --with-all-dependencies
```

### PHP Version Issues
If you're still running PHP 8.1:

1. Upgrade your PHP installation
2. Update your CI/CD pipelines
3. Update your production servers

### Testing Issues
If tests fail after upgrade:

```bash
composer install --dev
vendor/bin/phpunit
```

## Need Help?

- Check the [GitHub Issues](https://github.com/laravelmcp/mcp/issues)
- Review the [Documentation](https://mohamedahmed01.github.io/laravel-mcp-sdk/)
- Submit bug reports with detailed reproduction steps

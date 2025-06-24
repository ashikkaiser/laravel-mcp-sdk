# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Laravel 12.x compatibility support
- Support for PHP 8.3
- Extended Laravel version support (10.x, 11.x, 12.x)

### Changed
- **BREAKING**: Minimum PHP version raised to 8.2
- Updated Orchestra Testbench to support Laravel 12 (^8.0|^9.0|^10.0)
- Updated PHPUnit to support newer versions (^10.0|^11.0)
- Updated GitHub Actions workflow to test against Laravel 12
- Improved TestCase implementation using Orchestra Testbench
- Updated system requirements documentation

### Removed
- Dropped support for PHP 8.1
- Removed Laravel 9.x support (end of life)

### Migration Guide
To upgrade to Laravel 12 compatibility:

1. **Update PHP version**: Ensure you're running PHP 8.2 or higher
2. **Update dependencies**: Run `composer update` to get the latest compatible versions
3. **Update your application**: If you're using Laravel 10.x or 11.x, consider upgrading to Laravel 12.x
4. **Review breaking changes**: Check Laravel 12 upgrade guide for any breaking changes

### Technical Details
- `illuminate/support`: Updated to support `^10.0|^11.0|^12.0`
- `orchestra/testbench`: Updated to support `^8.0|^9.0|^10.0`
- All core functionality remains compatible across Laravel versions
- No breaking changes to the MCP SDK API
- Backward compatibility maintained for existing implementations

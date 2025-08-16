# Changelog

All notable changes to `laravel-attribute-collector` will be documented in this file.

## v1.0.0 - 2024-01-XX

### Added

- Initial release
- Route attributes for defining HTTP routes with `#[Route]`
- Event listener attributes with `#[Listen]`
- Scheduled job attributes with `#[Schedule]`
- Cache attributes for method result caching with `#[Cache]`
- Validation attributes with `#[Validate]`
- Authorization attributes with `#[Authorize]`
- Middleware attributes with `#[Middleware]`
- Rate limiting attributes with `#[RateLimit]`
- Logging attributes with `#[Log]`
- Comprehensive attribute handlers following ISP principles
- Artisan command for attribute discovery
- Full Laravel 9.x, 10.x, and 11.x support
- PHP 8.1+ support
- Zero runtime cost attribute collection
- Extensive documentation and examples

### Features

- **Zero Runtime Cost**: Attributes collected at build time using composer-attribute-collector
- **Type Safe**: Full PHP 8+ attribute support with proper type hints
- **Extensible**: Easy to create custom attribute handlers
- **Laravel Integration**: Seamless integration with Laravel's service container
- **Performance Optimized**: No reflection overhead during request handling

### Documentation

- Comprehensive README with usage examples
- Detailed examples for each attribute type
- Configuration documentation
- Contributing guidelines
- Security policy

### Testing

- PHPUnit test suite setup
- Orchestra Testbench integration
- Code quality tools (PHPStan)

## Unreleased

### Planned Features

- Additional attribute types (Queue, Broadcast, etc.)
- Enhanced caching strategies
- More authorization patterns
- Performance monitoring attributes
- Integration with Laravel Telescope

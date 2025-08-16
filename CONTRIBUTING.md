# Contributing to Laravel Attribute Collector

Thank you for considering contributing to Laravel Attribute Collector! This document outlines the process for contributing to this project.

## 🚀 Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer 2.0 or higher
- Laravel 9.0+ (for testing)

### Setup Steps

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   \`\`\`bash
   git clone https://github.com/your-username/laravel-attribute-collector.git
   cd laravel-attribute-collector
   \`\`\`

3. **Install dependencies**:
   \`\`\`bash
   composer install
   \`\`\`

4. **Run the test suite** to ensure everything works:
   \`\`\`bash
   composer test
   \`\`\`

5. **Run static analysis**:
   \`\`\`bash
   composer analyse
   \`\`\`

## 🔧 Development Workflow

### Branch Naming

Use descriptive branch names:
- `feature/add-new-attribute` - for new features
- `bugfix/fix-route-handling` - for bug fixes
- `docs/update-readme` - for documentation updates
- `refactor/improve-handler-structure` - for refactoring

### Making Changes

1. **Create a feature branch**:
   \`\`\`bash
   git checkout -b feature/your-feature-name
   \`\`\`

2. **Make your changes** following the coding standards
3. **Add tests** for new functionality
4. **Update documentation** if needed
5. **Run the test suite**:
   \`\`\`bash
   composer test
   composer analyse
   composer check-style
   \`\`\`

6. **Commit your changes** with descriptive messages:
   \`\`\`bash
   git commit -m "Add cache attribute with TTL support"
   \`\`\`

## 📝 Coding Standards

### PSR Standards
This project follows **PSR-12** coding standards. Please ensure your code adheres to these standards.

### Code Style
- Use **strict types** declaration: `declare(strict_types=1);`
- Use **type hints** for all parameters and return types
- Add **comprehensive docblocks** for all classes, methods, and properties
- Use **meaningful variable and method names**
- Keep methods **focused and single-purpose**

### Example Code Style

\`\`\`php
<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

/**
 * Handles processing of custom attributes.
 * 
 * This service is responsible for discovering and processing
 * custom attributes defined by developers.
 * 
 * @author Your Name <your.email@example.com>
 */
class CustomAttributeHandler implements AttributeHandlerInterface
{
    /**
     * Create a new custom attribute handler instance.
     * 
     * @param AttributeRegistry $registry The attribute registry service
     */
    public function __construct(
        private readonly AttributeRegistry $registry
    ) {
    }

    /**
     * Process all custom attributes discovered in the application.
     * 
     * @return void
     */
    public function handle(): void
    {
        $attributes = $this->registry->getAttributesByType(CustomAttribute::class);
        
        foreach ($attributes as $attribute) {
            $this->processAttribute($attribute);
        }
    }

    /**
     * Process a single custom attribute.
     * 
     * @param array $attributeData The attribute data to process
     * @return void
     */
    private function processAttribute(array $attributeData): void
    {
        // Implementation details
    }
}
\`\`\`

## 🧪 Testing

### Writing Tests

- **Unit tests** for individual classes and methods
- **Integration tests** for Laravel service provider integration
- **Feature tests** for end-to-end attribute processing

### Test Structure

\`\`\`php
<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Tests\Unit;

use Pixielity\LaravelAttributeCollector\Tests\TestCase;

/**
 * Test suite for CustomAttributeHandler.
 */
class CustomAttributeHandlerTest extends TestCase
{
    /**
     * Test that the handler can process attributes.
     * 
     * @test
     * @return void
     */
    public function it_can_process_custom_attributes(): void
    {
        // Test implementation
        $this->assertTrue(true);
    }
}
\`\`\`

### Running Tests

\`\`\`bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Unit/CustomAttributeHandlerTest.php

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse
\`\`\`

## 📚 Documentation

### Docblock Requirements

All public methods, classes, and properties must have comprehensive docblocks:

\`\`\`php
/**
 * Brief description of what this method does.
 * 
 * Longer description explaining the purpose, behavior,
 * and any important details about this method.
 * 
 * @param string $parameter Description of the parameter
 * @param array $options Optional configuration options
 * @return bool Returns true on success, false on failure
 * @throws InvalidArgumentException When parameter is invalid
 * 
 * @example
 * \`\`\`php
 * $result = $handler->processAttribute('example', ['option' => 'value']);
 * ```
 */
public function processAttribute(string $parameter, array $options = []): bool
{
    // Implementation
}
\`\`\`

### README Updates

When adding new features:
1. Update the feature list
2. Add usage examples
3. Update the available attributes section
4. Add any new configuration options

## 🔄 Pull Request Process

### Before Submitting

1. **Ensure all tests pass**:
   \`\`\`bash
   composer test
   composer analyse
   composer check-style
   \`\`\`

2. **Update documentation** if needed
3. **Add changelog entry** in `CHANGELOG.md`
4. **Rebase your branch** on the latest main:
   \`\`\`bash
   git rebase origin/main
   \`\`\`

### Pull Request Template

When creating a pull request, please include:

- **Clear title** describing the change
- **Description** of what was changed and why
- **Testing** information (how to test the changes)
- **Breaking changes** (if any)
- **Related issues** (if applicable)

### Example PR Description

\`\`\`markdown
## Description
Add support for caching method results using the `#[Cache]` attribute.

## Changes
- Added `Cache` attribute class
- Implemented `CacheAttributeHandler`
- Added comprehensive tests
- Updated documentation with examples

## Testing
- Added unit tests for `CacheAttributeHandler`
- Added integration tests with Laravel cache system
- All existing tests continue to pass

## Breaking Changes
None

## Related Issues
Closes #123
\`\`\`

## 🐛 Bug Reports

When reporting bugs, please include:

1. **Laravel version**
2. **PHP version**
3. **Package version**
4. **Steps to reproduce**
5. **Expected behavior**
6. **Actual behavior**
7. **Code examples** (if applicable)

## 💡 Feature Requests

For feature requests, please:

1. **Check existing issues** to avoid duplicates
2. **Describe the use case** clearly
3. **Provide examples** of how it would be used
4. **Consider backward compatibility**

## 📋 Code Review Process

All contributions go through code review:

1. **Automated checks** must pass (tests, static analysis, code style)
2. **Manual review** by maintainers
3. **Feedback incorporation** if needed
4. **Final approval** and merge

## 🏷️ Release Process

Releases follow semantic versioning:
- **Major** (x.0.0) - Breaking changes
- **Minor** (0.x.0) - New features, backward compatible
- **Patch** (0.0.x) - Bug fixes, backward compatible

## 🤝 Community Guidelines

- Be respectful and constructive
- Help others learn and grow
- Follow the code of conduct
- Share knowledge and best practices

## 📞 Getting Help

- **GitHub Issues** - For bugs and feature requests
- **GitHub Discussions** - For questions and community discussion
- **Email** - For security issues: your.email@example.com

Thank you for contributing to Laravel Attribute Collector! 🎉

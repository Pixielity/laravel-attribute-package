<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Validation Attribute for Automatic Request Validation
 *
 * This attribute enables automatic validation of controller method parameters
 * using Laravel's validation system. It provides a declarative way to define
 * validation rules without creating separate FormRequest classes.
 *
 * Features:
 * - Standard Laravel validation rules
 * - Custom error messages
 * - Conditional validation rules
 * - Multiple validation rule sets
 * - Integration with Laravel's validation system
 *
 * Usage Examples:
 *
 * Basic validation:
 * #[Validate(['email' => 'required|email', 'name' => 'required|string|max:255'])]
 * public function createUser(Request $request) { ... }
 *
 * With custom messages:
 * #[Validate(
 *     rules: ['email' => 'required|email'],
 *     messages: ['email.required' => 'Email is mandatory']
 * )]
 * public function updateProfile(Request $request) { ... }
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Validate
{
    /**
     * Create a new Validate attribute instance.
     *
     * @param array $rules              Laravel validation rules array
     * @param array $messages           Custom validation error messages
     * @param array $attributes         Custom attribute names for error messages
     * @param bool  $stopOnFirstFailure Whether to stop validation on first failure
     */
    public function __construct(
        /** @var array Laravel validation rules in 'field' => 'rules' format */
        public readonly array $rules = [],

        /** @var array Custom validation error messages */
        public readonly array $messages = [],

        /** @var array Custom attribute names for cleaner error messages */
        public readonly array $attributes = [],

        /** @var bool Stop validation on first failure for performance */
        public readonly bool $stopOnFirstFailure = false
    ) {}
}

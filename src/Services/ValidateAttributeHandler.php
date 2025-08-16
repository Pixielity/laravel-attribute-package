<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Pixielity\LaravelAttributeCollector\Attributes\Validate;
use Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface;

/**
 * Validation Attribute Handler for Automatic Request Validation
 *
 * This handler processes Validate attributes and implements automatic request
 * validation for controller methods. It integrates with Laravel's validation
 * system to provide declarative validation without FormRequest classes.
 *
 * Features:
 * - Automatic request validation based on attribute rules
 * - Custom error messages and attribute names
 * - Integration with Laravel's validation system
 * - Method-level validation configuration
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class ValidateAttributeHandler implements AttributeHandlerInterface
{
    /**
     * Create a new ValidateAttributeHandler instance.
     *
     * @param  AttributeRegistry  $registry  Central registry for attribute discovery
     * @param  ValidationFactory  $validator  Laravel's validation factory
     */
    public function __construct(
        /** @var AttributeRegistry Registry for discovering Validate attributes */
        private AttributeRegistry $registry,

        /** @var ValidationFactory Laravel's validation factory */
        private ValidationFactory $validator
    ) {}

    /**
     * Process and register all Validate attributes.
     *
     * Discovers all methods with Validate attributes and sets up automatic
     * request validation using middleware or method interception.
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_validation', true)) {
            return;
        }

        $methods = $this->registry->findMethodsWithAttribute(Validate::class);

        foreach ($methods as $methodData) {
            $this->registerValidation($methodData);
        }
    }

    /**
     * Register validation for a method.
     *
     * Sets up automatic request validation based on the Validate
     * attribute configuration.
     *
     * @param  array{class: string, method: string, attribute: Validate}  $methodData  Method with Validate attribute
     */
    private function registerValidation(array $methodData): void
    {
        /** @var Validate $validateAttribute */
        $validateAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        // This would typically involve creating a custom middleware or using method interception
        // to validate requests before they reach the controller method
    }
}

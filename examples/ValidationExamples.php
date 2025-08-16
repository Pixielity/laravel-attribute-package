<?php

declare(strict_types=1);

namespace Examples;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pixielity\LaravelAttributeCollector\Attributes\Validate;

/**
 * Validate Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Validate attribute
 * for automatic request validation.
 */
class ValidationExamples
{
    /**
     * Basic validation example
     *
     * Simple validation rules for user registration.
     * Validates required fields and formats.
     */
    #[Validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ])]
    public function register(Request $request): JsonResponse
    {
        // Validation is automatically applied before this method executes
        $user = User::create($request->validated());

        return response()->json(['user' => $user], 201);
    }

    /**
     * Validation with custom messages example
     *
     * Provides custom error messages for better user experience.
     * Overrides default Laravel validation messages.
     */
    #[Validate(
        rules: [
            'email' => 'required|email',
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|different:current_password',
        ],
        messages: [
            'email.required' => 'Email address is mandatory',
            'email.email' => 'Please provide a valid email address',
            'new_password.different' => 'New password must be different from current password',
        ]
    )]
    public function changePassword(Request $request): JsonResponse
    {
        // Custom validation messages will be used
        $this->updateUserPassword($request->validated());

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * Complex validation with custom attributes example
     *
     * Uses custom attribute names for cleaner error messages.
     * Useful for API responses with user-friendly field names.
     */
    #[Validate(
        rules: [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|regex:/^[0-9]{10}$/',
        ],
        messages: [
            'phone_number.regex' => 'Phone number must be exactly 10 digits',
        ],
        attributes: [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'date_of_birth' => 'Date of Birth',
            'phone_number' => 'Phone Number',
        ]
    )]
    public function updateProfile(Request $request): JsonResponse
    {
        $profile = $this->updateUserProfile($request->validated());

        return response()->json(['profile' => $profile]);
    }

    /**
     * File upload validation example
     *
     * Validates file uploads with size and type restrictions.
     * Includes multiple file validation rules.
     */
    #[Validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'documents.*' => 'file|mimes:pdf,doc,docx|max:10240',
        'title' => 'required|string|max:100',
    ])]
    public function uploadFiles(Request $request): JsonResponse
    {
        $avatarPath = $request->file('avatar')->store('avatars');
        $documentPaths = [];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $documentPaths[] = $document->store('documents');
            }
        }

        return response()->json([
            'avatar' => $avatarPath,
            'documents' => $documentPaths,
        ]);
    }

    /**
     * Conditional validation example
     *
     * Uses conditional validation rules based on other field values.
     * Demonstrates advanced validation scenarios.
     */
    #[Validate([
        'user_type' => 'required|in:individual,business',
        'company_name' => 'required_if:user_type,business|string|max:255',
        'tax_id' => 'required_if:user_type,business|string|max:50',
        'personal_id' => 'required_if:user_type,individual|string|max:50',
        'address' => 'required|string|max:500',
        'country' => 'required|string|size:2',
    ])]
    public function createAccount(Request $request): JsonResponse
    {
        $account = $this->createUserAccount($request->validated());

        return response()->json(['account' => $account], 201);
    }

    /**
     * API validation with stop on first failure example
     *
     * Stops validation on first failure for better API performance.
     * Useful for APIs where you want to return immediately on error.
     */
    #[Validate(
        rules: [
            'api_key' => 'required|string|size:32',
            'endpoint' => 'required|url',
            'method' => 'required|in:GET,POST,PUT,DELETE',
            'headers' => 'array',
            'headers.*' => 'string',
        ],
        stopOnFirstFailure: true
    )]
    public function makeApiCall(Request $request): JsonResponse
    {
        $response = $this->callExternalApi($request->validated());

        return response()->json(['response' => $response]);
    }

    /**
     * Nested array validation example
     *
     * Validates complex nested data structures.
     * Useful for bulk operations or complex form data.
     */
    #[Validate([
        'orders' => 'required|array|min:1',
        'orders.*.product_id' => 'required|integer|exists:products,id',
        'orders.*.quantity' => 'required|integer|min:1',
        'orders.*.price' => 'required|numeric|min:0',
        'orders.*.options' => 'array',
        'orders.*.options.*.name' => 'required|string|max:50',
        'orders.*.options.*.value' => 'required|string|max:100',
    ])]
    public function processBulkOrders(Request $request): JsonResponse
    {
        $orders = $this->createBulkOrders($request->validated()['orders']);

        return response()->json(['orders' => $orders], 201);
    }

    // Helper methods (would be implemented in real application)
    private function updateUserPassword(array $data): void
    { /* Implementation */
    }

    private function updateUserProfile(array $data): array
    {
        return [];
    }

    private function createUserAccount(array $data): array
    {
        return [];
    }

    private function callExternalApi(array $data): array
    {
        return [];
    }

    private function createBulkOrders(array $orders): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace Examples;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pixielity\LaravelAttributeCollector\Attributes\Middleware;
use Pixielity\LaravelAttributeCollector\Attributes\Route;

/**
 * Route Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Route attribute
 * for defining Laravel routes directly on controller methods.
 */
class RouteExamples
{
    /**
     * Basic GET route example
     *
     * Simple route definition with just URI and method.
     * Accessible at: GET /users
     */
    #[Route('GET', '/users')]
    public function index(): JsonResponse
    {
        return response()->json(['users' => []]);
    }

    /**
     * Named route with middleware example
     *
     * Route with name for URL generation and middleware protection.
     * Accessible at: GET /users/profile
     * Route name: users.profile
     */
    #[Route('GET', '/users/profile', name: 'users.profile', middleware: ['auth'])]
    public function profile(): JsonResponse
    {
        return response()->json(['profile' => auth()->user()]);
    }

    /**
     * POST route with validation middleware
     *
     * Route for creating resources with multiple middleware.
     * Accessible at: POST /users
     */
    #[Route('POST', '/users', name: 'users.store', middleware: ['auth', 'throttle:60,1'])]
    public function store(Request $request): JsonResponse
    {
        // Create user logic here
        return response()->json(['message' => 'User created'], 201);
    }

    /**
     * Route with parameter constraints
     *
     * Route with parameter validation using regex patterns.
     * Accessible at: GET /users/{id} where id must be numeric
     */
    #[Route('GET', '/users/{id}', name: 'users.show', where: ['id' => '[0-9]+'])]
    public function show(int $id): JsonResponse
    {
        return response()->json(['user' => ['id' => $id]]);
    }

    /**
     * PUT route for updates
     *
     * Route for updating entire resources.
     * Accessible at: PUT /users/{id}
     */
    #[Route('PUT', '/users/{id}', name: 'users.update', middleware: ['auth', 'can:update-user'])]
    public function update(Request $request, int $id): JsonResponse
    {
        // Update user logic here
        return response()->json(['message' => 'User updated']);
    }

    /**
     * PATCH route for partial updates
     *
     * Route for partial resource updates.
     * Accessible at: PATCH /users/{id}
     */
    #[Route('PATCH', '/users/{id}/status', name: 'users.update-status')]
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        // Update user status logic here
        return response()->json(['message' => 'Status updated']);
    }

    /**
     * DELETE route example
     *
     * Route for resource deletion with authorization.
     * Accessible at: DELETE /users/{id}
     */
    #[Route('DELETE', '/users/{id}', name: 'users.destroy', middleware: ['auth', 'can:delete-user'])]
    public function destroy(int $id): JsonResponse
    {
        // Delete user logic here
        return response()->json(['message' => 'User deleted']);
    }

    /**
     * Domain-specific route example
     *
     * Route that only responds to specific subdomain.
     * Accessible at: GET api.example.com/v1/users
     */
    #[Route('GET', '/v1/users', domain: 'api.{domain}', name: 'api.users.index')]
    public function apiIndex(): JsonResponse
    {
        return response()->json(['api_users' => []]);
    }
}

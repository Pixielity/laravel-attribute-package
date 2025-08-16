<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pixielity\LaravelAttributeCollector\Attributes\Middleware;
use Pixielity\LaravelAttributeCollector\Attributes\Route;

#[Middleware('auth')]
class ExampleController
{
    #[Route('GET', '/users', name: 'users.index')]
    #[Middleware('can:view-users')]
    public function index(): JsonResponse
    {
        return response()->json(['users' => []]);
    }

    #[Route('POST', '/users', name: 'users.store')]
    #[Middleware(['throttle:60,1', 'can:create-users'])]
    public function store(Request $request): JsonResponse
    {
        // Store user logic
        return response()->json(['message' => 'User created']);
    }

    #[Route('GET', '/users/{id}', name: 'users.show', where: ['id' => '[0-9]+'])]
    public function show(int $id): JsonResponse
    {
        return response()->json(['user' => ['id' => $id]]);
    }

    #[Route('PUT', '/users/{id}', name: 'users.update')]
    #[Route('PATCH', '/users/{id}', name: 'users.patch')]
    public function update(Request $request, int $id): JsonResponse
    {
        // Update user logic
        return response()->json(['message' => 'User updated']);
    }

    #[Route('DELETE', '/users/{id}', name: 'users.destroy')]
    public function destroy(int $id): JsonResponse
    {
        // Delete user logic
        return response()->json(['message' => 'User deleted']);
    }
}

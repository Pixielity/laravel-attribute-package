<?php

declare(strict_types=1);

namespace Examples;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pixielity\LaravelAttributeCollector\Attributes\Authorize;
use Pixielitys\Post;

/**
 * Authorize Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Authorize attribute
 * for declarative authorization in Laravel applications.
 */
class AuthorizeExamples
{
    /**
     * Policy-based authorization example
     *
     * Uses Laravel policy to check if user can update the post.
     * Automatically calls PostPolicy::update() method.
     */
    #[Authorize(policy: 'update', model: Post::class)]
    public function updatePost(Request $request, Post $post): JsonResponse
    {
        // Authorization is automatically checked before this method executes
        $post->update($request->validated());

        return response()->json(['post' => $post]);
    }

    /**
     * Gate-based authorization example
     *
     * Uses Laravel gate for simple authorization checks.
     * Checks if user passes the 'admin-only' gate.
     */
    #[Authorize(gate: 'admin-only')]
    public function adminPanel(): JsonResponse
    {
        // Only users passing 'admin-only' gate can access this
        $adminData = $this->getAdminDashboardData();

        return response()->json(['admin_data' => $adminData]);
    }

    /**
     * Role-based authorization example
     *
     * Checks if user has any of the specified roles.
     * Uses OR logic by default (user needs any of the roles).
     */
    #[Authorize(roles: ['admin', 'moderator'])]
    public function moderateContent(Request $request): JsonResponse
    {
        // User must have 'admin' OR 'moderator' role
        $this->performModerationAction($request->input('action'));

        return response()->json(['message' => 'Moderation action completed']);
    }

    /**
     * Permission-based authorization example
     *
     * Checks if user has specific permissions.
     * Uses OR logic by default (user needs any of the permissions).
     */
    #[Authorize(permissions: ['posts.create', 'posts.publish'])]
    public function createPost(Request $request): JsonResponse
    {
        // User must have 'posts.create' OR 'posts.publish' permission
        $post = $this->createNewPost($request->validated());

        return response()->json(['post' => $post], 201);
    }

    /**
     * Multiple roles with AND logic example
     *
     * Requires user to have ALL specified roles.
     * Uses requireAll flag for AND logic.
     */
    #[Authorize(roles: ['admin', 'super-user'], requireAll: true)]
    public function criticalSystemAction(): JsonResponse
    {
        // User must have BOTH 'admin' AND 'super-user' roles
        $this->performCriticalAction();

        return response()->json(['message' => 'Critical action completed']);
    }

    /**
     * Multiple permissions with AND logic example
     *
     * Requires user to have ALL specified permissions.
     * Useful for actions requiring multiple capabilities.
     */
    #[Authorize(permissions: ['users.delete', 'audit.write'], requireAll: true)]
    public function deleteUser(User $user): JsonResponse
    {
        // User must have BOTH 'users.delete' AND 'audit.write' permissions
        $this->auditUserDeletion($user);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Custom guard authorization example
     *
     * Uses specific authentication guard for authorization.
     * Useful for multi-guard applications (admin, api, etc.).
     */
    #[Authorize(roles: ['api-admin'], guard: 'api')]
    public function apiAdminEndpoint(): JsonResponse
    {
        // Uses 'api' guard to check for 'api-admin' role
        $apiData = $this->getApiAdminData();

        return response()->json(['data' => $apiData]);
    }

    /**
     * Complex policy authorization example
     *
     * Uses policy with specific model instance for authorization.
     * Demonstrates resource-specific authorization.
     */
    #[Authorize(policy: 'delete', model: Post::class)]
    public function deletePost(Post $post): JsonResponse
    {
        // Calls PostPolicy::delete($user, $post)
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Class-level authorization example
     *
     * When applied to a class, all methods require authorization.
     * Individual methods can override with their own attributes.
     */
    #[Authorize(roles: ['user'])]
    public function userOnlyMethod(): JsonResponse
    {
        // All methods in this class would require 'user' role
        // unless overridden by method-level attributes
        return response()->json(['message' => 'User-only content']);
    }

    /**
     * Combined authorization example
     *
     * Uses both roles and permissions for complex authorization.
     * Demonstrates flexible authorization scenarios.
     */
    #[Authorize(
        roles: ['editor', 'admin'],
        permissions: ['content.edit'],
        requireAll: false
    )]
    public function editContent(Request $request): JsonResponse
    {
        // User needs ('editor' OR 'admin' role) OR 'content.edit' permission
        $content = $this->updateContent($request->validated());

        return response()->json(['content' => $content]);
    }

    // Helper methods (would be implemented in real application)
    private function getAdminDashboardData(): array
    {
        return [];
    }

    private function performModerationAction(string $action): void
    { /* Implementation */
    }

    private function createNewPost(array $data): array
    {
        return [];
    }

    private function performCriticalAction(): void
    { /* Implementation */
    }

    private function auditUserDeletion(User $user): void
    { /* Implementation */
    }

    private function getApiAdminData(): array
    {
        return [];
    }

    private function updateContent(array $data): array
    {
        return [];
    }
}

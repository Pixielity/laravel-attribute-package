<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Authorization Attribute for Declarative Access Control
 *
 * This attribute provides declarative authorization for controller methods
 * using Laravel's authorization system. It automatically applies authorization
 * checks before method execution using policies, gates, or abilities.
 *
 * Features:
 * - Policy-based authorization
 * - Gate-based authorization
 * - Role and permission checking
 * - Custom authorization logic
 * - Integration with Laravel's authorization system
 *
 * Usage Examples:
 *
 * Policy-based authorization:
 * #[Authorize(policy: 'update', model: Post::class)]
 * public function updatePost(Post $post) { ... }
 *
 * Gate-based authorization:
 * #[Authorize(gate: 'admin-only')]
 * public function adminPanel() { ... }
 *
 * Role-based authorization:
 * #[Authorize(roles: ['admin', 'moderator'])]
 * public function moderateContent() { ... }
 *
 * Permission-based authorization:
 * #[Authorize(permissions: ['posts.create', 'posts.publish'])]
 * public function createPost() { ... }
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Authorize
{
    /**
     * Create a new Authorize attribute instance.
     *
     * @param  string|null  $policy  Policy method to check authorization
     * @param  string|null  $model  Model class for policy authorization
     * @param  string|null  $gate  Gate name for gate-based authorization
     * @param  array  $roles  Required user roles for access
     * @param  array  $permissions  Required permissions for access
     * @param  string|null  $guard  Authentication guard to use
     * @param  bool  $requireAll  Whether all roles/permissions are required (AND) or any (OR)
     */
    public function __construct(
        /** @var string|null Policy method name for model authorization */
        public readonly ?string $policy = null,

        /** @var string|null Model class name for policy-based authorization */
        public readonly ?string $model = null,

        /** @var string|null Gate name for gate-based authorization */
        public readonly ?string $gate = null,

        /** @var array Required user roles for access */
        public readonly array $roles = [],

        /** @var array Required permissions for access */
        public readonly array $permissions = [],

        /** @var string|null Authentication guard to use for authorization */
        public readonly ?string $guard = null,

        /** @var bool Whether all roles/permissions are required (true) or any (false) */
        public readonly bool $requireAll = false
    ) {}
}

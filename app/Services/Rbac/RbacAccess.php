<?php

namespace App\Services\Rbac;

/**
 * Class RbacAccess
 *
 * The RbacAccess class represents a set of predefined roles and permissions for Role-Based Access Control (RBAC).
 * It provides constant values for the initial Admin and User roles and their corresponding permissions.
 */
class RbacAccess
{
    /**
     * The RULE_ADMIN configuration defines the initial Admin role and permissions for it.
     *
     * @var array RULE_ADMIN
     */
    public const RULE_ADMIN = [
        'name' => 'Admin',
        'permissions' => [
            'admin' => 'admin.*'
        ]
    ];
    /**
     * The RULE_ADMIN configuration defines the initial User role and permissions for it.
     *
     * @var array RULE_ADMIN
     */
    public const RULE_USER = [
        'name' => \App\Models\User::class,
        'permissions' => [
            'show' => 'user.show'
        ]
    ];

}

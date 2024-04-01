<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Rbac\RbacAccess;
use Illuminate\Database\Seeder;
use Wnikk\LaravelAccessRules\AccessRules;
use Wnikk\LaravelAccessRules\Models\Rule;
use Wnikk\LaravelAccessRules\Models\Owner;
use Wnikk\LaravelAccessRules\Models\Permission;
use Wnikk\LaravelAccessRules\Models\Inheritance;

/**
 * Class CreateAdminRoleAndAddToUsersSeeder
 *
 * Seeder class for creating admin role and adding permissions to users.
 */
class CreateAdminRoleAndAddToUsersSeeder extends Seeder
{
    /**
     * Runs the process for initializing new access rules and adding permissions.
     *
     * This method performs the following tasks:
     * 1. Truncate all existing access rules.
     * 2. Initializes a new instance of AccessRules.
     * 3. Adds a new owner with the specified rule name.
     * 4. Adds permissions to the AccessRules instance.
     * 5. Inherit permissions for all users.
     *
     * @return void
     */
    public function run(): void
    {
        // Clear tables in db before insert new data
        $this->truncateAll();
        // Initialize new Access Rules
        $accessRules = new AccessRules();
        $ruleName = RbacAccess::RULE_ADMIN['name'];

        // Add new owner
        $accessRules->newOwner($ruleName, strtolower($ruleName), $ruleName . ' role');

        // Add permissions to Access Rules
        $this->addAdminPermissions($accessRules, $ruleName);

        // Inherit permissions for all users
        $this->addInheritedPermissionsForAllUsers();
    }

    /**
     * Truncates all records from the Rule, Owner, Permission, and Inheritance tables.     *
     * @return void
     */
    private function truncateAll(): void
    {
        Rule::truncate();
        Owner::truncate();
        Permission::truncate();
        Inheritance::truncate();
    }

    /**
     * Add Admin permissions to Access Rules.
     *
     * @param AccessRules $accessRules
     * @param string $ruleName
     */
    private function addAdminPermissions(AccessRules $accessRules, string $ruleName): void
    {
        foreach (RbacAccess::RULE_ADMIN['permissions'] as $permission) {
            AccessRules::newRule($permission, "$ruleName rule '$permission'");
            $accessRules->addPermission($permission);
        }
    }

    /**
     * Inherit permissions for all users.
     */
    private function addInheritedPermissionsForAllUsers(): void
    {
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            $user->inheritPermissionFrom(RbacAccess::RULE_ADMIN['name'], strtolower(RbacAccess::RULE_ADMIN['name']));
        }
    }
}

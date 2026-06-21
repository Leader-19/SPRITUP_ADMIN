<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            /**
             * users permission
             */
            "users.view",
            "users.create",
            "users.edit",
            "users.delete",
            /**
             * role permission
             */
            "roles.view",
            "roles.create",
            "roles.edit",
            "roles.delete",

            /**
             * documents permission
             */

            "document.create",
            "document.edit",
            "document.delete",
            "document.view",

            /**
             * Category
             */
            "category.create",
            "category.edit",
            "category.delete",
            "category.view",

        ];

        // foreach ($permissions as $key => $value) {

        //     Permission::create(["name" => $value, 'guard_name' => 'web',]);
        // }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Product
         */
        Permission::query()->insert([
            [
                'title' => 'create-product',
            ],
            [
                'title' => 'read-product',
            ],
            [
                'title' => 'update-product',
            ],
            [
                'title' => 'delete-product',
            ],
        ]);
        /**
         * User
         */
        Permission::query()->insert([
            [
                'title' => 'create-user',
            ],
            [
                'title' => 'read-user',
            ],
            [
                'title' => 'delete-user',
            ],
        ]);
        /**
         * Role
         */
        Permission::query()->insert([
            [
                'title' => 'create-role',
            ],
            [
                'title' => 'update-role',
            ],
            [
                'title' => 'read-role',
            ],
            [
                'title' => 'delete-role',
            ],
        ]);
    }
}

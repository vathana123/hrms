<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::create([
        //     'username' => '000000', 
        //     'password' => bcrypt('123456')
        // ]);

        // $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        Role::find(1)->syncPermissions($permissions);
        // User::find(1)->assignRole([$role->id]);
    }
}

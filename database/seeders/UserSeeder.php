<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory(10)->create();

        /** @var User $adminUser */
        $adminUser = User::factory()->create([
            'name'      => 'Admin',
            'last_name' => 'Admin',
            'email'     => 'admin@example.com',
            'username'  => 'admin',
            'password'  => password_hash('PassWord123!', PASSWORD_BCRYPT)
        ]);

        $adminRole = Role::query()->firstWhere(['name' => 'admin']);
        $adminUser->roles()->attach($adminRole);
    }
}

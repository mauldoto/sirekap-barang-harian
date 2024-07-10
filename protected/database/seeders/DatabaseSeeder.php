<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $admin = new User();
        $admin->name = 'JPN Superadmin';
        $admin->username = 'superadmin';
        $admin->email = 'jpn@putranetworks.net';
        $admin->password = bcrypt('superadmin#jpn');

        $admin->save();

        $admin = new User();
        $admin->name = 'JPN Admin';
        $admin->username = 'adminjpn';
        $admin->email = 'noc.jpn@gmail.com';
        $admin->password = bcrypt('jpn#pride');

        $admin->save();
    }
}

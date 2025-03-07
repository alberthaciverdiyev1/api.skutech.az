<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\User;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        User::create([
            'email' => 'admin@admin.com',
            'phone' => '0123456789',
            'otp' => null,
            'password' => Hash::make('admin'),
        ]);
    }
}

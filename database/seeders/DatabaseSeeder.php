<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Kunal';
        $user->username = 'kunal';
        $user->email = 'kunal@example.com';
        $user->password = bcrypt('12345678');
        $user->is_super = true;
        $user->save();
    }
}

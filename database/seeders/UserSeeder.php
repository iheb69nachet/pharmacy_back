<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::create([
            'username'=>'admin',
            'name' => 'admin ',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');
    }
}

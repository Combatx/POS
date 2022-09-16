<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ridho',
            'email' => 'ridho@gmail.com',
            'alamat' => 'Jl Karya',
            'telepon' => '089534564221',
            'password' => '$2y$10$Jtb3VPHdelkqqLbcVhVWIu1OxKqfeAjs.EnwvhG5FUvMpfxC/vI/O',
            'foto' => '/img/user1.png',
            'role_id' => 1,
        ]);
    }
}

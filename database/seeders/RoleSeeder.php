<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'admin',
            'gudang',
            'kasir',
        ];


        collect($roles)->map(function ($nama) {
            Role::query()
                ->updateOrCreate(compact('nama'), compact('nama'), compact('nama'));
        });
    }
}

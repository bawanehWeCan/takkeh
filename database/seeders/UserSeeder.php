<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $user->name = 'Super Admin';
        $user->email = 'admin@test.com';
        $user->phone = '0795401109';
        $user->password = Hash::make(1234);
        $user->save();
    }
}

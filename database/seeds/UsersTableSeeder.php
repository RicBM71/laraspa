<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      =>  'Root',
            'username'  => 'root',
            'email'     =>  'root@example.com',
            'password'  =>  Hash::make('root')
        ]);
    }
}

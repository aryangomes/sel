<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $userAdmin = factory(User::class)->make(
            [
                'email' => 'admin@email.com',
                'isAdmin' => 1,
            ]
        )->toArray();

        $userNotAdmin
            = factory(User::class)->make(
                [
                    'email' => 'user@email.com',

                ]
            )->toArray();

        $userAdmin['password'] = Hash::make(config('user.default_password_admin'));
        $userNotAdmin['password'] = Hash::make(config('user.default_password_not_admin'));


        $this->insertUserDatabase($userAdmin);
        $this->insertUserDatabase($userNotAdmin);
    }

    private function insertUserDatabase($user)
    {
        DB::table('users')->insert($user);
    }
}

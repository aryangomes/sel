<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                    'isAdmin' => 0,
                ]
            )->toArray();

        $userAdmin['password'] = bcrypt(env('DEFAULT_PASSWORD_ADMIN'));
        $userNotAdmin['password'] = bcrypt(env('DEFAULT_PASSWORD_ADMIN'));


        $this->insertUserDatabase($userAdmin);
        $this->insertUserDatabase($userNotAdmin);
    }

    private function insertUserDatabase($user)
    {
        DB::table('users')->insert($user);
    }
}

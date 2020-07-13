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
       /*  DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]); */

        $user =factory(User::class)->make(
            [
                'email' => 'admin@email.com',
                'isAdmin' =>1,
            ]
        )->toArray();

        $user['password']=bcrypt('12345678');
        DB::table('users')->insert($user);

    }
}

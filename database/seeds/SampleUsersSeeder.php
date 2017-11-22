<?php

use Illuminate\Database\Seeder;
use App\User;

class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname' => 'Pengkim',
            'lastname' => 'Sy',
            'email' => 'pengkim.test@gmail.com',
            'program' => 'Computer Science',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Lyrene',
            'lastname' => 'Labor',
            'email' => 'lyrene.test@gmail.com',
            'program' => 'Graphic Design',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Phillipe',
            'lastname' => 'IDK',
            'email' => 'phillipe.test@gmail.com',
            'program' => 'Civil Engineer',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Petter',
            'lastname' => 'IDK',
            'email' => 'petter.test@gmail.com',
            'program' => 'English Literature',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Werner',
            'lastname' => 'IDK',
            'email' => 'werner.test@gmail.com',
            'program' => 'Computer Science',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Daniel',
            'lastname' => 'Cava... something',
            'email' => 'daniel.test@gmail.com',
            'program' => 'Law',
            'password' => '123'
        ]);

        User::create([
            'firstname' => 'Ali',
            'lastname' => 'Dali',
            'email' => 'Ali.test@gmail.com',
            'program' => 'Graphic Design',
            'password' => '123'
        ]);
    }
}

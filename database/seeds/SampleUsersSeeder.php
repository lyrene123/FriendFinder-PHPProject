<?php

use Illuminate\Database\Seeder;
use App\User;

/**
 * Class SampleUsersSeeder that handles the seeding of the Users table with
 * mock data
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds for the Users table with mock data.
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
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Lyrene',
            'lastname' => 'Labor',
            'email' => 'lyrene.test@gmail.com',
            'program' => 'Graphic Design',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Phillipe',
            'lastname' => 'IDK',
            'email' => 'phillipe.test@gmail.com',
            'program' => 'Civil Engineer',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Petter',
            'lastname' => 'IDK',
            'email' => 'petter.test@gmail.com',
            'program' => 'English Literature',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Werner',
            'lastname' => 'IDK',
            'email' => 'werner.test@gmail.com',
            'program' => 'Computer Science',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Daniel',
            'lastname' => 'Cava... something',
            'email' => 'daniel.test@gmail.com',
            'program' => 'Law',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Ali',
            'lastname' => 'Dali',
            'email' => 'Ali.test@gmail.com',
            'program' => 'Graphic Design',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);
    }
}

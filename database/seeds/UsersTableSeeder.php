<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'firstname' => 'Lyrene',
            'lastname' => 'Labor',
            'email' => 'pizzalover@gmail.com',
            'program' => 'Computer Science',
            'password' => password_hash('pizzalover', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Pengkim',
            'lastname' => 'Sy',
            'email' => 'pengkimsy@gmail.com',
            'program' => 'Computer Science',
            'password' => password_hash('pengkimsy', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Peter',
            'lastname' => 'Bellefleur',
            'email' => 'peterbellefleur@gmail.com',
            'program' => 'Computer Science',
            'password' => password_hash('peterbellefleur', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Phil',
            'lastname' => 'Langlois',
            'email' => 'phillangois@gmail.com',
            'program' => 'Computer Science',
            'password' => password_hash('phillanglois', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Linda',
            'lastname' => 'Kelly',
            'email' => 'lindakelly@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('lindakelly', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Fred',
            'lastname' => 'Cox',
            'email' => 'fredcox@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('lindakelly', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Alice',
            'lastname' => 'Wood',
            'email' => 'alicewood@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('alicewood', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Helen',
            'lastname' => 'Baker',
            'email' => 'helenbaker@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('helenbaker', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Roy',
            'lastname' => 'Taylor',
            'email' => 'roytaylor@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('roytaylor', PASSWORD_DEFAULT),
        ]);

        User::create([
            'firstname' => 'Carlos',
            'lastname' => 'Young',
            'email' => 'carlosyoung@gmail.com',
            'program' => 'Nursing',
            'password' => password_hash('carlosyoung', PASSWORD_DEFAULT),
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Friend;

class SampleFriendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //'user_id', 'receiver_id','confirmed',

        Friend::create([
           'user_id' => 1,
           'receiver_id' => 2,
           'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 2,
            'receiver_id' => 1,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 1,
            'receiver_id' => 3,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 3,
            'receiver_id' => 1,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 1,
            'receiver_id' => 4,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 4,
            'receiver_id' => 1,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 2,
            'receiver_id' => 3,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 3,
            'receiver_id' => 2,
            'confirmed' => true
        ]);

        Friend::create([
            'user_id' => 2,
            'receiver_id' => 4,
            'confirmed' => false
        ]);

        Friend::create([
            'user_id' => 2,
            'receiver_id' => 5,
            'confirmed' => false
        ]);
    }
}

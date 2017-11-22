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
        //'sender_id', 'receiver_id','confirmed',

        Friend::create([
           'sender_id' => 1,
           'receiver_id' => 2,
           'confirmed' => true
        ]);

        Friend::create([
            'sender_id' => 1,
            'receiver_id' => 3,
            'confirmed' => true
        ]);

        Friend::create([
            'sender_id' => 1,
            'receiver_id' => 4,
            'confirmed' => true
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 3,
            'confirmed' => true
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 4,
            'confirmed' => true
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 5,
            'confirmed' => false
        ]);
    }
}

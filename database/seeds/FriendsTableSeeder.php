<?php

use Illuminate\Database\Seeder;
use App\Friend;
class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 3,
            'confirmed' => true,
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 4,
            'confirmed' => true,
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 5,
            'confirmed' => false,
        ]);

        Friend::create([
            'sender_id' => 6,
            'receiver_id' => 2,
            'confirmed' => false,
        ]);
    }
}

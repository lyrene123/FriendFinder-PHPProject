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
            'user_id' => 1,
            'receiver_id' => 2,
            'confirmed' => true,
        ]);

        Friend::create([
            'user_id' => 1,
            'receiver_id' => 3,
            'confirmed' => true,
        ]);

        Friend::create([
            'user_id' => 1,
            'receiver_id' => 4,
            'confirmed' => false,
        ]);

        Friend::create([
            'user_id' => 5,
            'receiver_id' => 1,
            'confirmed' => false,
        ]);
    }
}

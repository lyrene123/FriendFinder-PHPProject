<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(CourseScheduleTableSeeder::class);
        $this->call(SampleUsersSeeder::class);
        $this->call(SampleFriendsSeeder::class);
    }
}

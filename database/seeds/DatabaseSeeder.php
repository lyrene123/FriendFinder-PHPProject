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

        $this->call(SampleUsersSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(SampleFriendsSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(CourseTeacherTableSeeder::class);
        $this->call(CourseUserTableSeeder::class);
    }
}

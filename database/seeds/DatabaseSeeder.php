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
        $this->call(TeachersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);

      //  $this->call(CourseScheduleTableSeeder::class);
     //   $this->call(UsersTableSeeder::class);
    //    $this->call(FriendsTableSeeder::class);

        $this->call(CourseTeacherTableSeeder::class);
        $this->call(SampleUsersSeeder::class);
        $this->call(SampleFriendsSeeder::class);
        $this->call(CourseUserTableSeeder::class);
    }
}

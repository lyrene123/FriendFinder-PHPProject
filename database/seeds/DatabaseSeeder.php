<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder that handles the seeding of all tables
 * in the database.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds for all tables existing in the database.
     * All Seeder classes will then be called and executed in the
     * appropriate order specified below.
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

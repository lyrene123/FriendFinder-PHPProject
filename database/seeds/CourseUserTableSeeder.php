<?php

use Illuminate\Database\Seeder;
use App\CourseUser;

/**
 * Class CourseUserTableSeeder that handles the seeding of the Course_User table with data
 * taken from the CSV file provided by the teacher.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class CourseUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds for the Course_User table which mock data.
     * Data is used from the existing data from the User table and the courses table.
     *
     * @return void
     */
    public function run()
    {
        //'user_id', 'course_id'
        CourseUser::create([
            'user_id' => 1,
            'course_id' => 1
        ]);

        CourseUser::create([
            'user_id' => 1,
            'course_id' => 3
        ]);

        CourseUser::create([
            'user_id' => 2,
            'course_id' => 1
        ]);

        CourseUser::create([
            'user_id' => 2,
            'course_id' => 3
        ]);

        CourseUser::create([
            'user_id' => 3,
            'course_id' => 1
        ]);

        CourseUser::create([
            'user_id' => 3,
            'course_id' => 3
        ]);

        CourseUser::create([
            'user_id' => 4,
            'course_id' => 1
        ]);

        CourseUser::create([
            'user_id' => 4,
            'course_id' => 3
        ]);
    }
}

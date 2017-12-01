<?php

use Illuminate\Database\Seeder;
use App\CourseUser;

class CourseUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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

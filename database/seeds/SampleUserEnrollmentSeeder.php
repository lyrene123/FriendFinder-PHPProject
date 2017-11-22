<?php

use Illuminate\Database\Seeder;
use App\UserEnrollment;

class SampleUserEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //'user_id', 'course_id'
        UserEnrollment::create([
           'user_id' => 1,
           'course_id' => 1
        ]);

        UserEnrollment::create([
            'user_id' => 1,
            'course_id' => 3
        ]);

        UserEnrollment::create([
            'user_id' => 2,
            'course_id' => 2
        ]);

        UserEnrollment::create([
            'user_id' => 2,
            'course_id' => 4
        ]);
    }
}

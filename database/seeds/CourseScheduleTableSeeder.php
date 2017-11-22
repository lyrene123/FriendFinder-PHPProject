<?php

use Illuminate\Database\Seeder;
use App\CourseSchedule;

class CourseScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseSchedule::create(['class' => 'asdlf', 'section' => 'asldkfj', 'day' => 1, 'start' => 1000, 'end' => 1300, 'teacher_id' => 1, 'course_id' => 1]);
    }
}

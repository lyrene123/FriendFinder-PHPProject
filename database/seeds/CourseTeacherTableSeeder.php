<?php

use Illuminate\Database\Seeder;
use App\CourseTeacher;

class CourseTeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvPath = database_path('csv/FakeTeachersListW2017.csv');

        $csv = fopen($csvPath, 'r');

        fgetcsv($csv); // Skip the first line

        while(!feof($csv)){
            $linecsv = fgetcsv($csv);
            if($linecsv !== false) {
                $day = trim($linecsv[4]);
                $start = trim($linecsv[5]);
                $end = trim($linecsv[6]);

                $class = trim($linecsv[0]);
                $teachername = trim($linecsv[3]);

                $courseId = DB::table('courses')->where('class', '=', $class)->first()->id;
                $teacherId = DB::table('teachers')->where('name', '=', $teachername)->first()->id;

                CourseTeacher::create([
                    'day' => $day,
                    'start' => $start,
                    'end' => $end,
                    'teacher_id' => $teacherId,
                    'course_id' => $courseId
                ]);
            }
        }

        fclose($csv);
    }
}

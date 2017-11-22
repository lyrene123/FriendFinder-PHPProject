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
        $csvPath = database_path('csv/FakeTeachersListW2017.csv');

        $csv = fopen($csvPath, 'r');

        fgetcsv($csv); // Skip the first line

        $csvArr = array();
        while(!feof($csv)){

            $linecsv = fgetcsv($csv);
            if($linecsv !== false) {
                $section = trim($linecsv[1]);
                $day = trim($linecsv[4]);
                $start = trim($linecsv[5]);
                $end = trim($linecsv[6]);

                var_dump($linecsv);
                $class = trim($linecsv[0]);
                $teachername = trim($linecsv[3]);

                $courseId = DB::table('courses')->where('class', '=', $class)->first()->id;
                $teacherId = DB::table('teachers')->where('name', '=', $teachername)->first()->id;


                CourseSchedule::create([
                    'section' => $section,
                    'day' => $day,
                    'start' => $start,
                    'end' => $end,
                    'teacher_id' => $teacherId,
                    'course_id' => $courseId
                ]);
                $csvArr[$section] = $section;

            }
        }

        fclose($csv);
    }
}

<?php

use Illuminate\Database\Seeder;
use App\CourseTeacher;

/**
 * Class CourseTeacherTableSeeder that handles the seeding of the Course_Teacher table with data
 * taken from the CSV file provided by the teacher.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class CourseTeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds for the Course_Teacher table with the data
     * taken from the CSV file provided by the teacher.
     *
     * @return void
     */
    public function run()
    {
        $csvPath = database_path('csv/FakeTeachersListW2017.csv');

        $csv = fopen($csvPath, 'r');

        fgetcsv($csv); // Skip the first line

        //loop through each line of the CSV and retrieve the course-teacher information into a record in the table
        while(!feof($csv)){
            $linecsv = fgetcsv($csv); //separate the each line into an array

            //if not the last empty line
            if(isset($linecsv)) {
                //retrieve the relevant course-teacher info
                $day = trim($linecsv[4]);
                $start = trim($linecsv[5]);
                $end = trim($linecsv[6]);

                $class = trim($linecsv[0]);
                $teachername = trim($linecsv[3]);
                $section = trim($linecsv[1]);

                //retrieve the course id in the Courses table
                $courseId = DB::table('courses')
                    ->where('class', '=', $class)
                    ->where('section', '=', $section)
                    ->first()
                    ->id;

                //retrieve the teacher if in the Teacher table
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

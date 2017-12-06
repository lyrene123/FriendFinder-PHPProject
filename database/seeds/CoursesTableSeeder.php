<?php

use Illuminate\Database\Seeder;
use App\Course;

/**
 * Class CoursesTableSeeder that handles the seeding of the Courses table with data
 * taken from the CSV file provided by the teacher.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds for the Courses table in the database.
     * Data taken from the CSV file provided by the teacher.
     *
     * @return void
     */
    public function run()
    {
        $csvPath = database_path('csv/FakeTeachersListW2017.csv');

        $csv = fopen($csvPath, 'r');

        fgetcsv($csv); // Skip the first line

        //loop through each line of the CSV and retrieve the courses information into a record in the table
        $csvArr = array();
        while(!feof($csv)){
            $linecsv = fgetcsv($csv); //separate the each line into an array

            //if not the last empty line
            if(isset($linecsv)) {
                //retrieve the relevant course info
                $class = trim($linecsv[0]);
                $section = trim($linecsv[1]);
                $title = trim($linecsv[2]);

                //if array is empty or the course hasn't been added to the table yet, then add to the table
                if (!isset($csvArr[$section]) || $csvArr[$section] !== $class) {
                    Course::create([
                        'class' => $class,
                        'section' => $section,
                        'title' => $title
                    ]);
                    $csvArr[$section] = $class; //once added in the table, keep track the record in the array
                }
            }
        }
        fclose($csv);
    }
}

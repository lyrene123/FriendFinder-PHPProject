<?php

use Illuminate\Database\Seeder;
use App\Course;

class CoursesTableSeeder extends Seeder
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
                $class = trim($linecsv[0]);
                $section = trim($linecsv[1]);
                $title = trim($linecsv[2]);

                if (!isset($csvArr[$section]) || $csvArr[$section] !== $class) {
                    Course::create([
                        'class' => $class,
                        'section' => $section,
                        'title' => $title
                    ]);
                    $csvArr[$section] = $class;
                }
            }
        }

        fclose($csv);
    }
}

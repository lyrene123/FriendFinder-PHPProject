<?php

use Illuminate\Database\Seeder;
use App\Teacher;

/**
 * Class TeachersTableSeeder that handles the seeding of the Teachers table with data
 * taken from the CSV file provided by the teacher.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 */
class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds with data taken from the CSV file provided
     * by the teacher to fill up the Teachers table with records.
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

            if(isset($linecsv)) {
                $name = trim($linecsv[3]);

                if (!isset($csvArr[$name])) {
                    //echo "im hereeee";
                    Teacher::create(['name' => $name]);
                    $csvArr[$name] = $name;
                }
            }
        }

        fclose($csv);
    }
}

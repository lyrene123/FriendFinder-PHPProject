<?php

use Illuminate\Database\Seeder;
use App\Teacher;

class TeachersTableSeeder extends Seeder
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

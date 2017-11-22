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
//        DB::table('teachers')->insert([
//           'name' => "testteacher",
//        ]);
//        Teacher::create(['name' => 'testing 2']);
        $csvPath = database_path('csv/FakeTeachersListW2017.csv');

        $csv = fopen($csvPath, 'r');

        fgetcsv($csv); // Skip the first line

        /*while($data = fgetcsv($csv) !== FALSE) {
            var_dump($data);
            Teacher::create(['name' => $data['teacherName']]);
        }*/
        $csvArr = array();
        while(!feof($csv)){
            $linecsv = fgetcsv($csv);
            $name = substr(trim($linecsv[3]), 0, strlen($linecsv[3])-2);
           // Teacher::create(['name' => $name]);
           // array_push($csvArr, $name);

            //var_dump($csvArr);
            //var_dump($csvArr);
            if(!isset($csvArr[$name])){
                //echo "im hereeee";
                Teacher::create(['name' => $name]);
                $csvArr[$name] = $name;
            }
        }

        fclose($csv);




    }
}


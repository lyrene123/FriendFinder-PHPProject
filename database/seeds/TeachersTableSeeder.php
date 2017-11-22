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
        Teacher::created(['name' => 'testing']);
//        $csvPath = database_path('csv/FakeTeachersListW2017.csv');
//
//        $csv = fopen($csvPath);
//
//        fgetcsv($csv); // Skip the first line
//
//        while($data = fgetcsv($csv) !== FALSE) {
//            Teacher::created(['name' => $data['teacherName']]);
//        }

    }
}

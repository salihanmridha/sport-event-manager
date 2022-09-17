<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeekDay;

class WeekDayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array( 
        ['code' => 'mon','name' => 'Monday','creator_id' => 1],
        ['code' => 'tue','name' => 'Tuesday','creator_id' => 1],
        ['code' => 'wed','name' => 'Wednesday','creator_id' => 1],
        ['code' => 'thu','name' => 'Thusday','creator_id' => 1],
        ['code' => 'fri','name' => 'Friday','creator_id' => 1],
        ['code' => 'sat','name' => 'Saturday','creator_id' => 1],
        ['code' => 'sun','name' => 'Sunday','creator_id' => 1],
      );
      foreach ($data as $row) {
        WeekDay::firstOrCreate(
          ['code' => $row['code']],
          [
              'code' => $row['code'],
              'name' => $row['name'],
              'creator_id' => $row['creator_id']
          ]
        );
      }
    }
}

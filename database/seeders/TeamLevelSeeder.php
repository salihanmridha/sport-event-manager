<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamLevel;

class TeamLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array( 
        ['code' => 'school','name' => 'School','creator_id' => 1],
        ['code' => 'competitive','name' => 'Competitive','creator_id' => 1],
        ['code' => 'recreational','name' => 'Recreational','creator_id' => 1],
      );
      foreach ($data as $row) {
        TeamLevel::firstOrCreate(
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

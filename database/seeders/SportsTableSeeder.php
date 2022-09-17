<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array( 
        ['code' => 'volleyball','name' => 'Volleyball','creator_id' => 1],
        ['code' => 'basketball','name' => 'Basketball','creator_id' => 1],
        ['code' => 'karaoke','name' => 'Karaoke','creator_id' => 1],
        ['code' => 'tracks','name' => 'Tracks','creator_id' => 1],
        ['code' => 'squash','name' => 'Squash','creator_id' => 1],
        ['code' => 'american_football','name' => 'American Football','creator_id' => 1],
        ['code' => 'softball','name' => 'Softball','creator_id' => 1],
        ['code' => 'soccer','name' => 'Soccer','creator_id' => 1],
        ['code' => 'ski','name' => 'Ski','creator_id' => 1],
        ['code' => 'ping_pong','name' => 'Ping Pong','creator_id' => 1],
        ['code' => 'rings','name' => 'Rings','creator_id' => 1],
        ['code' => 'lacrosse','name' => 'Lacrosse','creator_id' => 1],
        ['code' => 'karate','name' => 'Karate','creator_id' => 1],
        ['code' => 'hockey','name' => 'Hockey','creator_id' => 1],
        ['code' => 'golf','name' => 'Golf','creator_id' => 1],
      );
      foreach ($data as $row) {
        try {
          Sport::firstOrCreate(
            ['code' => $row['code']],
            [
                'code' => $row['code'],
                'name' => $row['name'],
                'creator_id' => $row['creator_id']
            ]
          );
          //code...
        } catch (\Throwable $th) {
          echo $th->getMessage().'\n';
        }
      }
    }
}

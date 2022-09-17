<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VenueType;

class VenueTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array( 
        ['code' => 'arenas','name' => 'Arenas','creator_id' => 1],
        ['code' => 'gymnasium','name' => 'Gymnasium','creator_id' => 1],
        ['code' => 'stadiums','name' => 'Stadiums','creator_id' => 1],
        ['code' => 'studios','name' => 'Studios','creator_id' => 1],
        ['code' => 'rinks','name' => 'Rinks','creator_id' => 1],
        ['code' => 'pools','name' => 'Pools','creator_id' => 1],
        ['code' => 'outdoor_fields','name' => 'Outdoor Fields','creator_id' => 1],
      );
      foreach ($data as $row) {
        VenueType::firstOrCreate(
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

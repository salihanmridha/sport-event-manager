<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactRelationship;

class ContactRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array( 
        ['code' => 'parent','name' => 'Parent','creator_id' => 1],
        ['code' => 'sibling','name' => 'Sibling','creator_id' => 1],
        ['code' => 'spouse','name' => 'Spouse','creator_id' => 1],
        ['code' => 'children','name' => 'Children','creator_id' => 1],
        ['code' => 'relative','name' => 'Relative','creator_id' => 1],
        ['code' => 'guardian','name' => 'Guardian','creator_id' => 1],
        ['code' => 'friend','name' => 'Friend','creator_id' => 1],
      );
      foreach ($data as $row) {
        ContactRelationship::firstOrCreate(
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

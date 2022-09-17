<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrganizationRole;

class OrganizationRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = array(
        ['code' => 'volunteer','name' => 'Volunteer','creator_id' => 1], 
        ['code' => 'player','name' => 'Player','creator_id' => 1], 
        ['code' => 'manager','name' => 'Manager','creator_id' => 1], 
        ['code' => 'coach','name' => 'Coach','creator_id' => 1],
        ['code' => 'secretary','name' => 'Secretary','creator_id' => 1],
        ['code' => 'board_member','name' => 'Board Member','creator_id' => 1], 
        ['code' => 'president','name' => 'President','creator_id' => 1], 
      );
      foreach ($data as $row) {
        OrganizationRole::firstOrCreate(
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

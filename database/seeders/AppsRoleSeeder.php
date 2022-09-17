<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
          [            
            'title' => 'Team Owner',
            'type' => 1,
          ],
          [
            'title' => 'Team Manager',
            'type' => 1,
          ],
          [
            'title' => 'Team Coach',
            'type' => 1,
          ],
          [
            'title' => 'Team Player',
            'type' => 1,
          ],
        ];
        //DB::table('roles')->upsert($role, ['title', 'type']);
        foreach ($role as $row) {
            if (isset($row['title']) && !empty($row['title'])) {
                Role::firstOrCreate(
                  ['title' => $row['title']],
                  [
                      'title' => $row['title'],
                      'type' => (isset($row['type']) && is_int($row['type'])) ? intval($row['type']) : 0
                  ]
                );
            }
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            AppsRoleSeeder::class,
            AppsPermissionRoleSeeder::class,
            CountriesSeeder::class,
            CurrenciesSeeder::class,
            TeamLevelSeeder::class,
            OrganizationRoleSeeder::class,
            ContactRelationshipSeeder::class,
            VenueTypeTableSeeder::class,
            WeekDayTableSeeder::class,
            SportsTableSeeder::class,
        ]);
    }
}

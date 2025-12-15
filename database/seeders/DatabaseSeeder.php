<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Master data
            RoleSeeder::class,
            DesignationSeeder::class,
            ZoneSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            ParliamentSeatSeeder::class,
            PoliticalPartySeeder::class,
            ProgramTypeSeeder::class,

            // Users
            UserSeeder::class,

            // Reports
            ReportSeeder::class,
        ]);
    }
}

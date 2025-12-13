<?php
namespace Database\Seeders;

use Database\Seeders\DesignationSeeder;
use Database\Seeders\ParliamentSeatSeeder;
use Database\Seeders\PoliticalPartySeeder;
use Database\Seeders\ProgramTypeSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UpazilaSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ZoneSeeder;
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
            ParliamentSeatSeeder::class,
            PoliticalPartySeeder::class,
            ProgramTypeSeeder::class,
            UserSeeder::class,

            // Reports
            ReportSeeder::class,
        ]);
    }
}

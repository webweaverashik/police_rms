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
            RoleSeeder::class,
            DesignationSeeder::class,

            ParliamentSeatSeeder::class,
            UpazilaSeeder::class,
            ZoneSeeder::class,
            UnionSeeder::class,

            UserSeeder::class,

            PoliticalPartySeeder::class,
            SeatPartyCandidateSeeder::class,
            ProgramTypeSeeder::class,

            ReportSeeder::class,
            ReportAssignmentSeeder::class,
        ]);
    }
}

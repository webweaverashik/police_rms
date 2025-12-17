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
            /*
            |--------------------------------------------------------------------------
            | Core Master Data
            |--------------------------------------------------------------------------
            */
            RoleSeeder::class,
            DesignationSeeder::class,
            ZoneSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Political Master Data
            |--------------------------------------------------------------------------
            */
            ParliamentSeatSeeder::class,
            PoliticalPartySeeder::class,
            SeatPartyCandidateSeeder::class, // 🔴 REQUIRED

            /*
            |--------------------------------------------------------------------------
            | Program Master Data
            |--------------------------------------------------------------------------
            */
            ProgramTypeSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            UserSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Reports & Assignments
            |--------------------------------------------------------------------------
            */
            ReportSeeder::class,
            ReportAssignmentSeeder::class,
        ]);
    }
}

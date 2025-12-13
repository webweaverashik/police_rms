<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProgramTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProgramType::insert([
            ['name' => 'Rally'],
            ['name' => 'Meeting'],
        ]);

    }
}

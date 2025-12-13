<?php
namespace Database\Seeders;

use App\Models\PoliticalParty;
use Illuminate\Database\Seeder;

class PoliticalPartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PoliticalParty::insert([
            ['name' => 'Awami League'],
            ['name' => 'BNP'],
        ]);

    }
}

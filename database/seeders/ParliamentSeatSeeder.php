<?php
namespace Database\Seeders;

use App\Models\ParliamentSeat;
use Illuminate\Database\Seeder;

class ParliamentSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParliamentSeat::insert([
            ['name' => 'Jessore-1', 'description' => 'Jessore Sadar'],
        ]);
    }
}

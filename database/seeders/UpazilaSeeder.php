<?php
namespace Database\Seeders;

use App\Models\Upazila;
use Illuminate\Database\Seeder;

class UpazilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Upazila::insert([
            ['name' => 'Jessore Sadar'],
            ['name' => 'Jhikargacha'],
        ]);
    }
}

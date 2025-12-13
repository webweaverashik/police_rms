<?php
namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Designation::insert([
            ['name' => 'Superintendent of Police (SP)'],
            ['name' => 'Additional Superintendent of Police (ADC)'],
            ['name' => 'Upazila Nirbahi Officer (UNO)'],
            ['name' => 'Assistant Superintendent of Police (ASP)'],
            ['name' => 'Officer in Charge (OC)'],
            ['name' => 'Inspector'],
            ['name' => 'Sub Inspector (SI)'],
            ['name' => 'Assistant Sub Inspector (ASI)'],
            ['name' => 'Constable'],
        ]);

    }
}

<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'           => 'Super Admin',
            'designation_id' => 1,
            'role_id'        => 1,
            'mobile_no'      => '01700000000',
            'email'          => 'admin@prms.gov',
            'password'       => bcrypt('password'),
        ]);

    }
}

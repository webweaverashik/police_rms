<?php
namespace Database\Seeders;

use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use App\Models\User\Designation;
use App\Models\Administrative\Zone;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('uploads/all_users.xlsx');

        if (! file_exists($filePath)) {
            $this->command->error('Excel file not found: ' . $filePath);
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray();

        // Remove header row
        $header = array_map('strtolower', array_shift($rows));

        foreach ($rows as $index => $row) {

            if (empty($row[0]) || empty($row[4])) {
                continue; // Skip empty rows
            }

            $data = array_combine($header, $row);

            $role        = Role::where('name', trim($data['role']))->first();
            $designation = Designation::where('name', trim($data['designation']))->first();

            if (! $role || ! $designation) {
                $this->command->warn("Skipped row " . ($index + 2) . ": Role or Designation missing");
                continue;
            }

            $zoneId = null;
            if (! empty($data['zone'])) {
                $zoneId = Zone::where('name', trim($data['zone']))->value('id');
            }

            User::updateOrCreate(
                ['email' => trim($data['email'])],
                [
                    'name'           => trim($data['name']),
                    'bp_number'      => $data['bp_number'] ?: null,
                    'designation_id' => $designation->id,
                    'zone_id'        => $zoneId,
                    'role_id'        => $role->id,
                    'mobile_no'      => trim($data['phone']),
                    'password'       => Hash::make('password123'),
                    'is_active'      => true,
                ]
            );
        }

        $this->command->info('Users imported successfully from Excel.');
    }
}

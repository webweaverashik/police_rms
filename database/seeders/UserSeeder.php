<?php
namespace Database\Seeders;

use App\Models\Administrative\Zone;
use App\Models\User\Designation;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles (MUST exist before running this seeder)
        |--------------------------------------------------------------------------
        | SuperAdmin  → Full system control (ICT / System owner)
        | Admin       → Police Super (SP)
        | Viewer      → OC, ADC, ASP (Zone-based viewers)
        | Magistrate  → UNO (Assigned-report viewer only)
        | Operator    → Field level (SI, ASI, Inspector, Constable)
        |--------------------------------------------------------------------------
        */
        $superAdminRole = Role::where('name', 'SuperAdmin')->firstOrFail();
        $adminRole      = Role::where('name', 'Admin')->firstOrFail();
        $viewerRole     = Role::where('name', 'Viewer')->firstOrFail();
        $magistrateRole = Role::where('name', 'Magistrate')->firstOrFail();
        $operatorRole   = Role::where('name', 'Operator')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Designations (Bangla)
        |--------------------------------------------------------------------------
        */
        $ict       = Designation::where('name', 'আইসিটি অফিসার')->firstOrFail();
        $sp        = Designation::where('name', 'পুলিশ সুপার (এসপি)')->firstOrFail();
        $adc       = Designation::where('name', 'অতিরিক্ত পুলিশ সুপার')->firstOrFail();
        $uno       = Designation::where('name', 'উপজেলা নির্বাহী কর্মকর্তা')->firstOrFail();
        $oc        = Designation::where('name', 'অফিসার ইনচার্জ')->firstOrFail();
        $inspector = Designation::where('name', 'ইন্সপেক্টর')->firstOrFail();
        $si        = Designation::where('name', 'সাব-ইন্সপেক্টর')->firstOrFail();
        $asi       = Designation::where('name', 'অ্যাসিস্ট্যান্ট সাব-ইন্সপেক্টর')->firstOrFail();
        $constable = Designation::where('name', 'কনস্টেবল')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Zones (Bangla)
        |--------------------------------------------------------------------------
        */
        $kalapara        = Zone::where('name', 'কলাপাড়া থানা')->firstOrFail();
        $galachipa       = Zone::where('name', 'গলাচিপা থানা')->firstOrFail();
        $dashmina        = Zone::where('name', 'দশমিনা থানা')->firstOrFail();
        $dumki           = Zone::where('name', 'দুমকি থানা')->firstOrFail();
        $patuakhaliSadar = Zone::where('name', 'পটুয়াখালী সদর থানা')->firstOrFail();
        $bauphal         = Zone::where('name', 'বাউফল থানা')->firstOrFail();
        $mohipur         = Zone::where('name', 'মহিপুর থানা')->firstOrFail();
        $mirzaganj       = Zone::where('name', 'মির্জাগঞ্জ থানা')->firstOrFail();
        $rangabali       = Zone::where('name', 'রাঙ্গাবালী থানা')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Super Admin (ICT / System Owner)
        |--------------------------------------------------------------------------
        */
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@prms.gov'],
            [
                'name'           => 'সবুজ তালুকদার',
                'bp_number'      => null,
                'designation_id' => $ict->id,
                'zone_id'        => null,
                'role_id'        => $superAdminRole->id,
                'mobile_no'      => '01700000000',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Admin (Police Super - SP)
        |--------------------------------------------------------------------------
        */
        $spUser = User::updateOrCreate(
            ['email' => 'sp@prms.gov'],
            [
                'name'           => 'মোঃ আবু ইউসুফ',
                'bp_number'      => '10001',
                'designation_id' => $sp->id,
                'zone_id'        => null,
                'role_id'        => $adminRole->id,
                'mobile_no'      => '01700000001',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Viewers (ADC, OC)
        |--------------------------------------------------------------------------
        */

        // ADC
        $adcUser = User::updateOrCreate(
            ['email' => 'adc@prms.gov'],
            [
                'name'           => 'মোঃ অপু সরোয়ার',
                'bp_number'      => '20001',
                'designation_id' => $adc->id,
                'zone_id'        => $patuakhaliSadar->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000002',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        // OC
        $ocUser = User::updateOrCreate(
            ['email' => 'oc@prms.gov'],
            [
                'name'           => 'মোঃ সৈয়দুজ্জামান',
                'bp_number'      => '20002',
                'designation_id' => $oc->id,
                'zone_id'        => $galachipa->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000004',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Magistrate (UNO) – Assigned-report viewer only
        |--------------------------------------------------------------------------
        | NOTE:
        | UNO access comes from report_assignments table,
        | NOT from zones.
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'uno@prms.gov'],
            [
                'name'           => 'কাউছার হামিদ',
                'bp_number'      => null,
                'designation_id' => $uno->id,
                'zone_id'        => null,
                'role_id'        => $magistrateRole->id,
                'mobile_no'      => '01700000003',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Operators (Field Level)
        |--------------------------------------------------------------------------
        */

        // Inspector
        $inspectorUser = User::updateOrCreate(
            ['email' => 'inspector@prms.gov'],
            [
                'name'           => 'মোঃ মাসুদ হোসেন',
                'bp_number'      => '30001',
                'designation_id' => $inspector->id,
                'zone_id'        => $dumki->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000005',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        // SI
        $siUser = User::updateOrCreate(
            ['email' => 'si@prms.gov'],
            [
                'name'           => 'আব্দুর রহিম মৃধা',
                'bp_number'      => '30002',
                'designation_id' => $si->id,
                'zone_id'        => $bauphal->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000006',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        // ASI
        $asiUser = User::updateOrCreate(
            ['email' => 'asi@prms.gov'],
            [
                'name'           => 'মোঃ হুমায়ুন কবির',
                'bp_number'      => '30003',
                'designation_id' => $asi->id,
                'zone_id'        => $mohipur->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000007',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        // Constable
        $constableUser = User::updateOrCreate(
            ['email' => 'constable@prms.gov'],
            [
                'name'           => 'মোঃ নওশের আলী',
                'bp_number'      => '30004',
                'designation_id' => $constable->id,
                'zone_id'        => $rangabali->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000008',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
    }
}

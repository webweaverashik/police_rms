<?php
namespace Database\Seeders;

use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        $adminRole    = Role::where('name', 'Administrator')->firstOrFail();
        $viewerRole   = Role::where('name', 'Viewer')->firstOrFail();
        $operatorRole = Role::where('name', 'Operator')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Designations
        |--------------------------------------------------------------------------
        */
        $sp        = Designation::where('name', 'Superintendent of Police (SP)')->firstOrFail();
        $adc       = Designation::where('name', 'Additional Superintendent of Police (ADC)')->firstOrFail();
        $uno       = Designation::where('name', 'Upazila Nirbahi Officer (UNO)')->firstOrFail();
        $oc        = Designation::where('name', 'Officer in Charge (OC)')->firstOrFail();
        $inspector = Designation::where('name', 'Inspector')->firstOrFail();
        $si        = Designation::where('name', 'Sub Inspector (SI)')->firstOrFail();
        $asi       = Designation::where('name', 'Assistant Sub Inspector (ASI)')->firstOrFail();
        $constable = Designation::where('name', 'Constable')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Zones (Bangla)
        |--------------------------------------------------------------------------
        */
        $kalapara        = Zone::where('name', 'কলাপাড়া জোন')->firstOrFail();
        $kalaparaPayra   = Zone::where('name', 'কলাপাড়া জোন (পায়রা বন্দর)')->firstOrFail();
        $galachipa       = Zone::where('name', 'গলাচিপা জোন')->firstOrFail();
        $dashmina        = Zone::where('name', 'দশমিনা জোন')->firstOrFail();
        $dumki           = Zone::where('name', 'দুমকি জোন')->firstOrFail();
        $patuakhaliSadar = Zone::where('name', 'পটুয়াখালী সদর জোন')->firstOrFail();
        $bauphal         = Zone::where('name', 'বাউফল জোন')->firstOrFail();
        $mahpur          = Zone::where('name', 'মহিপুর জোন')->firstOrFail();
        $mirzaganj       = Zone::where('name', 'মির্জাগঞ্জ জোন')->firstOrFail();
        $rangabali       = Zone::where('name', 'রাঙ্গাবালী জোন')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Administrator (Full Access)
        |--------------------------------------------------------------------------
        */
        $admin = User::updateOrCreate(
            ['email' => 'admin@prms.gov'],
            [
                'name'           => 'System Administrator',
                'designation_id' => $sp->id,
                'role_id'        => $adminRole->id,
                'mobile_no'      => '01700000000',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );

        // Admin → ALL zones
        $admin->zones()->sync([
            $kalapara->id,
            $kalaparaPayra->id,
            $galachipa->id,
            $dashmina->id,
            $dumki->id,
            $patuakhaliSadar->id,
            $bauphal->id,
            $mahpur->id,
            $mirzaganj->id,
            $rangabali->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Viewers (SP, ADC, UNO, OC)
        |--------------------------------------------------------------------------
        */

        // SP
        $spUser = User::updateOrCreate(
            ['email' => 'sp@prms.gov'],
            [
                'name'           => 'District SP',
                'designation_id' => $sp->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000001',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $spUser->zones()->sync([$patuakhaliSadar->id]);

        // ADC
        $adcUser = User::updateOrCreate(
            ['email' => 'adc@prms.gov'],
            [
                'name'           => 'Additional SP',
                'designation_id' => $adc->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000002',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $adcUser->zones()->sync([$patuakhaliSadar->id]);

        // UNO
        $unoUser = User::updateOrCreate(
            ['email' => 'uno@prms.gov'],
            [
                'name'           => 'UNO',
                'designation_id' => $uno->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000003',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $unoUser->zones()->sync([$kalapara->id]);

        // OC (Viewer as requested)
        $ocUser = User::updateOrCreate(
            ['email' => 'oc@prms.gov'],
            [
                'name'           => 'Officer in Charge',
                'designation_id' => $oc->id,
                'role_id'        => $viewerRole->id,
                'mobile_no'      => '01700000004',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $ocUser->zones()->sync([$galachipa->id]);

        /*
        |--------------------------------------------------------------------------
        | Operators (Field Level)
        |--------------------------------------------------------------------------
        */

        // Inspector
        $inspectorUser = User::updateOrCreate(
            ['email' => 'inspector@prms.gov'],
            [
                'name'           => 'Inspector',
                'designation_id' => $inspector->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000005',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $inspectorUser->zones()->sync([$dumki->id]);

        // SI
        $siUser = User::updateOrCreate(
            ['email' => 'si@prms.gov'],
            [
                'name'           => 'Sub Inspector',
                'designation_id' => $si->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000006',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $siUser->zones()->sync([$bauphal->id]);

        // ASI
        $asiUser = User::updateOrCreate(
            ['email' => 'asi@prms.gov'],
            [
                'name'           => 'Assistant Sub Inspector',
                'designation_id' => $asi->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000007',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $asiUser->zones()->sync([$mahpur->id]);

        // Constable
        $constableUser = User::updateOrCreate(
            ['email' => 'constable@prms.gov'],
            [
                'name'           => 'Constable',
                'designation_id' => $constable->id,
                'role_id'        => $operatorRole->id,
                'mobile_no'      => '01700000008',
                'password'       => Hash::make('password123'),
                'is_active'      => true,
            ]
        );
        $constableUser->zones()->sync([$rangabali->id]);
    }
}
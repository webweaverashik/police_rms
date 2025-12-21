<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Administrative\Zone;
use App\Models\User\Designation;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! auth()->user()->isSuperAdmin()) {
            return redirect()->route('reports.index')->with('warning', 'এই লিংকে আপনার অনুমতি নেই');
        }

        $users = User::with(['role:id,name', 'designation:id,name', 'zone:id,name', 'loginActivities'])
            ->withCount('reports')
            ->latest()
            ->get();

        // Filter Data
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! auth()->user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('warning', 'এই লিংকে আপনার অনুমতি নেই');
        }
        $roles        = Role::all();
        $zones        = Zone::all();
        $designations = Designation::all();

        return view('users.create', compact('roles', 'zones', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // -------------------------------
        // Validation
        // -------------------------------
        $validated = $request->validate(
            [
                'name'           => ['required', 'string', 'max:255'],
                'bp_number'      => ['required', 'numeric', 'unique:users,bp_number'],
                'designation_id' => ['required', 'exists:designations,id'],
                'role_id'        => ['required', 'exists:roles,id'],
                'zone_id'        => ['nullable', 'exists:zones,id'],
                'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
                'mobile_no'      => ['required', 'regex:/^01[3-9][0-9]{8}$/'],
            ],
            [
                'bp_number.numeric' => 'বিপি নাম্বার শুধুমাত্র সংখ্যা হতে হবে।',
                'mobile_no.regex'   => 'একটি সঠিক বাংলাদেশি মোবাইল নাম্বার দিন।',
            ],
        );

        DB::beginTransaction();

        try {
            // -------------------------------
            // Create User
            // -------------------------------
            $user = User::create([
                'name'           => $validated['name'],
                'bp_number'      => $validated['bp_number'],
                'designation_id' => $validated['designation_id'],
                'zone_id'        => $validated['zone_id'] ?? null,
                'role_id'        => $validated['role_id'],
                'email'          => $validated['email'],
                'mobile_no'      => $validated['mobile_no'],
                'password'       => Hash::make('1234@#'), // default password
                'is_active'      => true,
            ]);

            DB::commit();

            return response()->json(
                [
                    'success'  => true,
                    'message'  => 'ইউজার সফলভাবে তৈরি হয়েছে।',
                    'redirect' => route('users.index'),
                ],
                201,
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(
                [
                    'success' => false,
                    'message' => 'ইউজার তৈরি করতে সমস্যা হয়েছে।',
                    'error'   => config('app.debug') ? $e->getMessage() : null,
                ],
                500,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('errors.404');
    }

    /**
     * Display the user profile page
     */
    public function profile()
    {
        $user            = User::find(auth()->user()->id);
        $loginActivities = $user->loginActivities()->latest()->get();

        return view('users.profile', compact('user', 'loginActivities'));
    }

    /*
     * Update user personal profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'      => 'required|string|max:255',
            'bp_number' => ['required', 'numeric', Rule::unique('users')->ignore($user->id)],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile_no' => ['required', 'regex:/^01[3-9]\d{8}$/', Rule::unique('users')->ignore($user->id)],
        ];

        // Remove uniqueness rule if value didn't change (optional but nice)
        if ($request->email === $user->email) {
            unset($rules['email']);
        }
        if ($request->mobile_no === $user->mobile_no) {
            unset($rules['mobile_no']);
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে।',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (! auth()->user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('warning', 'এই লিংকে আপনার অনুমতি নেই');
        }
        $roles        = Role::all();
        $zones        = Zone::all();
        $designations = Designation::all();

        return view('users.edit', compact('user', 'roles', 'zones', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // -------------------------------
        // Validation
        // -------------------------------
        $validated = $request->validate(
            [
                'name'           => ['required', 'string', 'max:255'],
                'bp_number'      => ['nullable', 'numeric'],
                'designation_id' => ['required', 'exists:designations,id'],
                'role_id'        => ['required', 'exists:roles,id'],
                'email'          => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
                'mobile_no'      => ['required', 'regex:/^01[3-9][0-9]{8}$/'],
                'zone_id'        => ['nullable', 'exists:zones,id'],
            ],
            [
                'bp_number.numeric' => 'বিপি নাম্বার শুধুমাত্র সংখ্যা হতে হবে।',
                'mobile_no.regex'   => 'একটি সঠিক বাংলাদেশি মোবাইল নাম্বার দিন।',
            ],
        );

        DB::beginTransaction();

        try {
            // -------------------------------
            // Update User
            // -------------------------------
            $user->update([
                'name'           => $validated['name'],
                'bp_number'      => $validated['bp_number'] ?? null,
                'designation_id' => $validated['designation_id'],
                'zone_id'        => $validated['zone_id'] ?? null,
                'role_id'        => $validated['role_id'],
                'email'          => $validated['email'],
                'mobile_no'      => $validated['mobile_no'],
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'ইউজার সফলভাবে আপডেট হয়েছে।',
                'redirect' => route('users.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(
                [
                    'success' => false,
                    'message' => 'ইউজার আপডেট করতে সমস্যা হয়েছে।',
                    'error'   => config('app.debug') ? $e->getMessage() : null,
                ],
                500,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Toggle active and inactive users
     */
    public function toggleActive(Request $request)
    {
        $user = User::find($request->user_id);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Error. Please, contact support.']);
        }

        $user->is_active = $request->is_active;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User activation status updated.']);
    }

    /**
     * Reset user password
     */
    public function userPasswordReset(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
}

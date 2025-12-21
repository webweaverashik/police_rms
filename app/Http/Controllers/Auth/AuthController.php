<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\LoginActivity;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('warning', 'আপনি এখনও লগিন অবস্থায় আছেন।');
        }
        return view('auth.login')->with('warning', 'অনুগ্রহ পূর্বক পুনরায় লগিন করুন।');
    }

    // Handle login
    public function login(Request $request)
    {
        /**
         * ------------------------------------
         * 1. Basic validation (AJAX friendly)
         * ------------------------------------
         */
        $validator = Validator::make($request->all(), [
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                ],
                422,
            );
        }

        /**
         * ------------------------------------
         * 2. Detect login field
         * ------------------------------------
         * If valid email → use email
         * Otherwise → treat as BP number
         */
        $loginValue = trim($request->login);

        $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'bp_number';

        /**
         * ------------------------------------
         * 3. Fetch user (including soft deleted)
         * ------------------------------------
         */
        $user = User::withTrashed()->where($loginField, $loginValue)->first();

        if (! $user) {
            return response()->json(
                [
                    'message' => 'এই ইমেইল বা BP নম্বরে কোনো ইউজার নেই।',
                ],
                401,
            );
        }

        /**
         * ------------------------------------
         * 4. Account state checks
         * ------------------------------------
         */
        if ($user->trashed()) {
            return response()->json(
                [
                    'message' => 'আপনার আইডি বন্ধ রয়েছে।',
                ],
                403,
            );
        }

        if (! $user->is_active) {
            return response()->json(
                [
                    'message' => 'আপনার আইডি সাময়িকভাবে বন্ধ রয়েছে। অনুগ্রহ করে এডমিনের সাথে যোগাযোগ করুন।',
                ],
                403,
            );
        }

        /**
         * ------------------------------------
         * 5. Attempt authentication
         * ------------------------------------
         */
        if (
            ! Auth::attempt([
                $loginField => $loginValue,
                'password'  => $request->password,
            ])
        ) {
            return response()->json(
                [
                    'message' => 'ইউজার বা পাসওয়ার্ড ভুল হয়েছে।',
                ],
                401,
            );
        }

        /**
         * ------------------------------------
         * 6. Login success → store activity
         * ------------------------------------
         */
        $user = Auth::user();

        LoginActivity::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'device'     => $this->detectDevice($request->header('User-Agent')),
        ]);

        /**
         * ------------------------------------
         * 7. Success response
         * ------------------------------------
         */
        return response()->json([
            'message'  => 'সাইন ইন সফল হয়েছে।',
            'redirect' => $user->role->name === 'Operator' ? route('dashboard') : route('reports.index'),
        ]);
    }

    // Helper function to detect device type
    private function detectDevice($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'সফলভাবে সাইন আউট হয়েছে।');
    }
}

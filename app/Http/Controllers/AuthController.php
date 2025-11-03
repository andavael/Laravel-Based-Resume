<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect()->route('resume.edit');
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Throttle login attempts (5 attempts per minute)
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Clear login attempts
            if (method_exists($this, 'clearLoginAttempts')) {
                $this->clearLoginAttempts($request);
            }

            // Log successful login
            Log::info('User logged in', [
                'user_id' => Auth::id(),
                'username' => Auth::user()->username,
                'ip' => $request->ip()
            ]);

            return redirect()->intended(route('resume.edit'));
        }

        // Increment login attempts
        if (method_exists($this, 'incrementLoginAttempts')) {
            $this->incrementLoginAttempts($request);
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Show registration page
     */
    public function showRegister()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect()->route('resume.edit');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'sr_code' => [
                'required',
                'string',
                'regex:/^[0-9]{2}-[0-9]{5}$/',
                'unique:users,sr_code'
            ],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:50',
                'unique:users,username'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/@g\.batstate-u\.edu\.ph$/',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'sr_code.regex' => 'SR Code must follow the format: 23-04485',
            'email.regex' => 'Please use your BatStateU email address (@g.batstate-u.edu.ph)',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        try {
            // Create user
            $user = User::create([
                'sr_code' => $validated['sr_code'],
                'username' => $validated['username'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
            ]);

            // Log registration
            Log::info('New user registered', [
                'user_id' => $user->id,
                'username' => $user->username,
                'sr_code' => $user->sr_code
            ]);

            return redirect()
                ->route('login')
                ->with('success', 'Registration successful! Please log in to continue.');

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'username' => $validated['username']
            ]);

            return back()
                ->withInput($request->only('sr_code', 'username', 'email'))
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        $username = Auth::user()->username ?? 'Unknown';

        // Get the logged-out user's resume ID before logging them out
        try {
            $userResume = DB::table('resume')
                ->where('user_id', $userId)
                ->first();

            $resumeId = $userResume ? $userResume->id : null;
        } catch (\Exception $e) {
            Log::error('Failed to fetch user resume', ['error' => $e->getMessage()]);
            $resumeId = null;
        }

        // Logout user
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log logout
        Log::info('User logged out', [
            'user_id' => $userId,
            'username' => $username,
            'ip' => $request->ip()
        ]);

        // If we couldn't find the user's resume, get the most recently updated one
        if (!$resumeId) {
            try {
                $latest = DB::table('resume')
                    ->orderBy('updated_at', 'desc')
                    ->first();

                $resumeId = $latest ? $latest->id : 1;
            } catch (\Exception $e) {
                Log::error('Failed to fetch latest resume', ['error' => $e->getMessage()]);
                $resumeId = 1;
            }
        }

        return redirect()
            ->route('resume.public', ['id' => $resumeId])
            ->with('info', 'You have been logged out successfully.');
    }

    /**
     * Get the maximum number of login attempts
     */
    protected function maxAttempts()
    {
        return 5;
    }

    /**
     * Get the lockout duration in seconds
     */
    protected function decayMinutes()
    {
        return 1;
    }
}
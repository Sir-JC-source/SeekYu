<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\RegisteredUsers;

class LoginController extends Controller
{
    public function index()
    {
        return view('Login.login');
    }

    public function register()
    {
        return view('Login.register');
    }

    public function clientRegister()
    {
        return view('Login.client-register');
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'company_name'  => 'required|string|max:255',
            'email'          => [
                'required',
                'email',
                'unique:registered_users,email',
            ],
            'password'              => 'required|string|min:8|confirmed',
            'contact_number'        => 'required|string|max:20',
            'province'              => 'required|string|max:255',
            'city'                  => 'required|string|max:255',
            'barangay'              => 'required|string|max:255',
        ]);

        // Generate a unique login_id for the client (e.g., slug + timestamp or 4-digit random unique number)
        $loginIdBase = preg_replace('/\s+/', '', strtolower($request->company_name));
        $loginId = $loginIdBase . substr(time(), -4);

        // Ensure login_id uniqueness, fallback to random 4-digit number if duplicate
        if (RegisteredUsers::where('login_id', $loginId)->exists()) {
            do {
                $loginId = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            } while (RegisteredUsers::where('login_id', $loginId)->exists());
        }

        $user = new RegisteredUsers();
        $user->fullname       = $request->company_name; // Company name as fullname or create a separate field later if needed
        $user->email          = $request->email;
        $user->login_id       = $loginId;
        $user->password       = Hash::make($request->password);
        $user->contact_no     = $request->contact_number;
        $user->province       = $request->province;
        $user->city           = $request->city;
        $user->barangay       = $request->barangay;
        $user->role           = 'client';
        $user->account_status = 'pending'; // Pending approval or verification flow as needed
        $user->first_login    = true;
        $user->save();

        $user->assignRole('client');

        // Generate verification URL
        $verificationUrl = route('email.verify', ['id' => $user->id, 'token' => sha1($user->email . $user->created_at)]);

        // Send email with credentials and verification link
        \Mail::to($user->email)->send(new \App\Mail\LoginCredentialsMail($user, $verificationUrl));

        return redirect()->route('login.client-register')
            ->with('success', 'Registration successful! Please check your email for login credentials and verification link.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name'      => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'email'          => [
                'required',
                'email',
                'unique:registered_users,email',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['gmail.com', 'yahoo.com'];
                    $emailDomain = substr(strrchr($value, "@"), 1);
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('The ' . $attribute . ' must be a valid email address from: ' . implode(', ', $allowedDomains) . '.');
                    }
                }
            ],
            'password'                 => 'required|string|min:8',
            'password_confirmation'    => 'required|same:password',
            'contact_number'           => 'required|string|max:20',
            'province'                 => 'required|string|max:255',
            'city'                     => 'required|string|max:255',
            'barangay'                 => 'required|string|max:255',
        ]);

        $fullname = trim($request->last_name . ', ' . $request->first_name . ($request->middle_name ? ' ' . $request->middle_name : ''));

        // Generate unique 4-digit login ID
        do {
            $loginId = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (RegisteredUsers::where('login_id', $loginId)->exists());

        $user = new RegisteredUsers();
        $user->fullname       = $fullname;
        $user->email          = $request->email;
        $user->login_id       = $loginId;
        $user->password       = Hash::make($request->password);
        $user->contact_no     = $request->contact_number;
        $user->province       = $request->province;
        $user->city           = $request->city;
        $user->barangay       = $request->barangay;
        $user->role           = 'applicant'; // All registrants become applicants
        $user->account_status = 'pending'; // Pending email verification
        $user->first_login    = true;
        $user->save();

        $user->assignRole('applicant');

        // Generate verification URL
        $verificationUrl = route('email.verify', ['id' => $user->id, 'token' => sha1($user->email . $user->created_at)]);

        // Send email with credentials and verification link
        \Mail::to($user->email)->send(new \App\Mail\LoginCredentialsMail($user, $verificationUrl));

        return redirect()->route('login.index')
            ->with('success', 'Registration successful! Please check your email for login credentials and verification link.');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // SUPER ADMIN LOGIN (via fullname)
        $superAdmin = RegisteredUsers::where('fullname', $username)
            ->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))
            ->first();

        if ($superAdmin && Hash::check($password, $superAdmin->password)) {
            Auth::login($superAdmin, $request->has('remember'));
            $request->session()->regenerate();
            return redirect()->route('dashboard.index')
                ->with('success', 'Welcome Super Admin!');
        }

        // FACULTY LOGIN
        $faculty = RegisteredUsers::where('faculty_no', $username)
            ->whereHas('roles', fn($q) => $q->where('name', 'faculty'))
            ->first();

        if ($faculty) {
            if (!in_array(strtolower($faculty->account_status), ['approved', 'active'])) {
                return back()->with('error', 'Your faculty account is not yet approved.');
            }

            if (!Hash::check($password, $faculty->password)) {
                return back()->with('error', 'Invalid faculty password.');
            }

            Auth::login($faculty, $request->has('remember'));
            $request->session()->regenerate();

            if ($faculty->first_login) session(['force_password_change' => true]);

            return redirect()->route('dashboard.index')->with('success', 'Welcome Faculty!');
        }

        // STUDENT LOGIN
        $student = RegisteredUsers::where('student_no', $username)
            ->whereHas('roles', fn($q) => $q->where('name', 'student'))
            ->first();

        if ($student) {
            if (!in_array(strtolower($student->account_status), ['approved', 'active'])) {
                return back()->with('error', 'Your student account is not yet approved.');
            }

            if (!Hash::check($password, $student->password)) {
                return back()->with('error', 'Invalid student password.');
            }

            Auth::login($student, $request->has('remember'));
            $request->session()->regenerate();

            if ($student->first_login) session(['force_password_change' => true]);

            return redirect()->route('dashboard.index')->with('success', 'Welcome Student!');
        }

        // OTHER ROLES (Admin, HR, Head Guard, Guard, Client, Applicant)
        $otherRoles = ['admin', 'hr-officer', 'security-guard', 'head-guard', 'client', 'applicant'];

        $user = RegisteredUsers::where('login_id', $username)
            ->whereHas('roles', fn($q) => $q->whereIn('name', $otherRoles))
            ->first();

        if ($user) {
            if (!in_array(strtolower($user->account_status), ['approved', 'active'])) {
                return back()->with('error', 'Your account is not yet approved.');
            }

            // Check if email is verified (only for applicants)
            if ($user->role === 'applicant' && !$user->email_verified_at) {
                return back()->with('error', 'Please verify your email address before logging in.');
            }

            if (!Hash::check($password, $user->password)) {
                return back()->with('error', 'Invalid password.');
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            // Update last_login timestamp
            $user->last_login = now();
            $user->save();

            if ($user->first_login) session(['force_password_change' => true]);

            // Redirect based on role
            if (in_array($user->role, ['security-guard', 'head-guard'])) {
                return redirect()->route('attendance.index')
                    ->with('success', 'Welcome ' . ucfirst(str_replace('-', ' ', $user->role)) . '!');
            } elseif ($user->role === 'applicant') {
                return redirect()->route('applicant.jobs')
                    ->with('success', 'Welcome ' . ucfirst(str_replace('-', ' ', $user->role)) . '!');
            }

            return redirect()->route('dashboard.index')
                ->with('success', 'Welcome ' . ucfirst(str_replace('-', ' ', $user->role)) . '!');
        }

        return back()->with('error', 'No matching account found.');
    }

    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->save();

        session()->forget('force_password_change');

        return redirect()->route('dashboard.index')->with('success', 'Password updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index')->with('success', 'You have been logged out successfully.');
    }

    public function verifyEmail(Request $request, $id, $token)
    {
        $user = RegisteredUsers::findOrFail($id);

        // Verify token
        $expectedToken = sha1($user->email . $user->created_at);
        if ($token !== $expectedToken) {
            return redirect()->route('login.index')->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('login.index')->with('info', 'Email already verified. You can now login.');
        }

        // Mark as verified
        $user->email_verified_at = now();
        $user->account_status = 'approved';
        $user->save();

        return redirect()->route('login.index')->with('success', 'Email verified successfully! You can now login with your credentials.');
    }
}

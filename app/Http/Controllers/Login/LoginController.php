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

    public function store(Request $request)
    {
        $request->validate([
            'fullname'    => 'required|string|max:255',
            'login_id'    => 'required|digits:5|unique:registered_users,login_id',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => 'required|in:admin,hr-officer,security-guard,head-guard,client,applicant,student,faculty',
        ]);

        $user = new RegisteredUsers();
        $user->fullname       = $request->fullname;
        $user->login_id       = $request->login_id;
        $user->password       = Hash::make($request->password);
        $user->role           = $request->role;
        $user->account_status = 'pending'; // default until approved
        $user->first_login    = true;
        $user->save();

        $user->assignRole($request->role);

        return redirect()->route('login.index')
            ->with('success', 'Registration submitted! Please wait for admin approval.');
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

            if (!Hash::check($password, $user->password)) {
                return back()->with('error', 'Invalid password.');
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            if ($user->first_login) session(['force_password_change' => true]);

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
}

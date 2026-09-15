<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show teacher/admin login view.
     */
    public function showUserLoginForm()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            return redirect()->route($user->isSuperadmin() ? 'admin.dashboard' : 'guru.dashboard');
        }
        return view('auth.login-user');
    }

    /**
     * Authenticate teacher/admin.
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        if (Auth::guard('web')->attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            ActivityLog::record('USER_LOGIN', "Pengguna {$user->name} ({$user->role}) berhasil masuk.", $user);

            return redirect()->intended(route($user->isSuperadmin() ? 'admin.dashboard' : 'guru.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password yang dimasukkan salah / tidak aktif.',
        ])->onlyInput('username');
    }

    /**
     * Show student NIS login view.
     */
    public function showStudentLoginForm()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('siswa.dashboard');
        }
        return view('auth.login-student');
    }

    /**
     * Authenticate student using NIS.
     */
    public function loginStudent(Request $request)
    {
        $credentials = $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ], [
            'nis.required' => 'NIS tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        $student = Student::where('nis', $credentials['nis'])->first();

        if ($student && $student->is_active && Hash::check($credentials['password'], $student->password)) {
            Auth::guard('student')->login($student, $request->boolean('remember'));
            $request->session()->regenerate();

            $student->update(['last_login_at' => now()]);

            ActivityLog::record('STUDENT_LOGIN', "Siswa {$student->name} (NIS: {$student->nis}) berhasil masuk.", null, $student);

            return redirect()->route('siswa.dashboard');
        }

        return back()->withErrors([
            'nis' => 'NIS atau password salah, atau akun Anda nonaktif.',
        ])->onlyInput('nis');
    }

    /**
     * Logout teacher/admin.
     */
    public function logoutUser(Request $request)
    {
        $user = Auth::guard('web')->user();
        if ($user) {
            ActivityLog::record('USER_LOGOUT', "Pengguna {$user->name} keluar dari sistem.", $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Logout student.
     */
    public function logoutStudent(Request $request)
    {
        $student = Auth::guard('student')->user();
        if ($student) {
            ActivityLog::record('STUDENT_LOGOUT', "Siswa {$student->name} keluar dari portal.", null, $student);
        }

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }
}

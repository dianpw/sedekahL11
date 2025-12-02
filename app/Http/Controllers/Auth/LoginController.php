<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Ganti redirect path default jika tidak menggunakan trait
    protected $redirectTo = '/'; // Akan diarahkan secara dinamis

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Tampilkan form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validasi kredensial login.
     * Kita gunakan username (format SED-*****) atau email untuk login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string', // Bisa username atau email
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
    }

    /**
     * Dapatkan nama field yang digunakan untuk login.
     * Kita ganti dari 'email' ke 'username'.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Format SED-***** diasumsikan sebagai username
    }

    /**
     * Ambil kredensial dari request.
     * Kita sesuaikan untuk menerima input dari field 'username_or_email'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
        // atau secara eksplisit:
        // return [
        //     'username' => $request->input('username'), // Nama field input di form
        //     'password' => $request->input('password'),
        // ];
    }

    /**
     * Redirect berdasarkan role user setelah login berhasil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Log aktivitas login
        \App\Models\Log::create([
            'id_users' => $user->id_users,
            'action' => 'login',
            'description' => 'User berhasil login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->role === 'member') {
            return redirect()->intended('/member/dashboard');
        }

        // Jika role tidak dikenali, logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withErrors([$this->username() => 'Akun tidak memiliki akses yang valid.']);
    }

    /**
     * Logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            // Log aktivitas logout
            \App\Models\Log::create([
                'id_users' => $user->id_users,
                'action' => 'logout',
                'description' => 'User logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Arahkan ke halaman login setelah logout
    }
}

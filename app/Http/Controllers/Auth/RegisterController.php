<?php

namespace App\Http\Controllers;

use App\Models\MarketingPin;
use App\Models\MemberProfile;
use App\Models\Pin;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function showRegisterForm()
    {
        // Ambil data provinsi untuk dropdown
        $provinces = \App\Models\Province::all();
        return view('auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'alpha_num',
                'unique:users,username',
                'regex:/^SED-[A-Za-z0-9]{6}$/', // Format SED-******
            ],
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'dana_account' => 'required|string|max:20', // Validasi nomor HP
            'sponsor_username' => 'required|string|exists:users,username,role,member', // Harus username member aktif
            // Validasi alamat
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'provinsi_id' => 'required|exists:provinces,id_provinsi',
            'kab_kota_id' => 'required|exists:regencies,id_kab_kota',
            'kecamatan_id' => 'required|exists:districts,id_kecamatan',
            'desa_id' => 'required|exists:villages,id_desa',
            // Validasi PIN
            'pin_code' => 'required|string',
        ], [
            'username.regex' => 'Format username harus SED- diikuti 6 karakter alfanumerik.',
            'sponsor_username.exists' => 'Sponsor tidak ditemukan atau bukan member aktif.',
            'pin_code.required' => 'Kode PIN wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pinCode = $request->pin_code;
        $sponsorUsername = $request->sponsor_username;

        $user = null;
        $profile = null;

        DB::transaction(function () use ($request, $sponsorUsername, &$user, &$profile, $pinCode) {
            // Cek apakah PIN adalah PIN Marketing yang valid dan belum digunakan
            $marketingPin = MarketingPin::where('pin_code', $pinCode)
                ->where('is_used', false)
                ->where('valid_until', '>', now())
                ->first();

            if ($marketingPin) {
                // Pendaftaran dengan PIN Marketing
                $registrationType = 'marketing';
                $pinUsedId = $marketingPin->id_marketing_pins;
                $sponsorUser = User::where('username', $sponsorUsername)->first(); // Sponsor tetap diperlukan untuk pendaftaran
                if (!$sponsorUser) {
                    throw new \Exception('Sponsor tidak valid.');
                }
            } else {
                // Cek apakah PIN adalah PIN Normal dan milik sponsor
                $sponsorUser = User::where('username', $sponsorUsername)->first();
                $sponsorPin = Pin::where('user_id', $sponsorUser->id_users)->first();

                if (!$sponsorPin || $sponsorPin->balance < 1) {
                    throw new \Exception('PIN Normal tidak valid atau saldo sponsor tidak mencukupi.');
                }

                $registrationType = 'normal';
                $pinUsedId = null; // Karena PIN Normal dihabiskan, bukan dicatat di tabel registrasi
            }

            // 1. Buat User
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'member',
                'dana_account' => $request->dana_account,
                'is_active' => true,
            ]);

            // 2. Buat Member Profile
            $profile = MemberProfile::create([
                'user_id' => $user->id_users,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'id_sponsor' => $sponsorUsername,
                'id_upline' => null, // Akan diisi oleh logika "sistem matahari" setelah pendaftaran
                'provinsi_id' => $request->provinsi_id,
                'kab_kota_id' => $request->kab_kota_id,
                'kecamatan_id' => $request->kecamatan_id,
                'desa_id' => $request->desa_id,
            ]);

            // 3. Buat Record Registrasi
            $registration = Registration::create([
                'new_member_id' => $user->id_users,
                'sponsor_username' => $sponsorUsername,
                'upline_username' => null, // Akan diisi oleh logika "sistem matahari"
                'registration_type' => $registrationType,
                'marketing_pin_id' => $pinUsedId,
                'registration_fee' => $registrationType === 'normal' ? 100000 : null,
                'admin_fee' => $registrationType === 'normal' ? 15000 : null,
            ]);

            // 4. Jika pendaftaran normal, kurangi saldo PIN sponsor
            if ($registrationType === 'normal') {
                $sponsorPin->decrement('balance', 1);
            }

            // 5. Jika pendaftaran marketing, tandai PIN Marketing sebagai digunakan
            if ($registrationType === 'marketing') {
                $marketingPin->update(['is_used' => true, 'assigned_user_id' => $user->id_users]);
            }

            // 6. Logika "Sistem Matahari" untuk menentukan upline dan hitung bonus (IMPLEMENTASI LAINNYA)
            // $this->applySunSystemLogic($registration, $sponsorUsername);

            // 7. Berikan akses ke produk digital default
            $defaultProducts = \App\Models\DigitalProduct::where('access_level', 'all_members')->get();
            foreach ($defaultProducts as $product) {
                \App\Models\MemberProductAccess::create([
                    'user_id' => $user->id_users,
                    'product_id' => $product->id_digital_products,
                ]);
            }
        });

        if ($user && $profile) {
            // Login user baru secara otomatis
            Auth::login($user);
            return redirect()->route('member.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Program Sedekah.');
        }

        return back()->withErrors(['general' => 'Terjadi kesalahan saat mendaftar.'])->withInput();
    }

    // Fungsi untuk mengambil kabupaten/kota berdasarkan provinsi (untuk dropdown dinamis)
    public function getRegencies($provinceId)
    {
        $regencies = \App\Models\Regency::where('province_id', $provinceId)->get(['id_kab_kota', 'name']);
        return response()->json($regencies);
    }

    // Fungsi untuk mengambil kecamatan berdasarkan kabupaten/kota (untuk dropdown dinamis)
    public function getDistricts($regencyId)
    {
        $districts = \App\Models\District::where('regency_id', $regencyId)->get(['id_kecamatan', 'name']);
        return response()->json($districts);
    }

    // Fungsi untuk mengambil desa/kelurahan berdasarkan kecamatan (untuk dropdown dinamis)
    public function getVillages($districtId)
    {
        $villages = \App\Models\Village::where('district_id', $districtId)->get(['id_desa', 'name']);
        return response()->json($villages);
    }
}

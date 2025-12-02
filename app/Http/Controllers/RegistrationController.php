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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function showRegisterForm()
    {
        $provinces = \App\Models\Province::all();
        return view('auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'alpha_num',
                'unique:users,username',
                'regex:/^SED-[A-Za-z0-9]{6}$/',
            ],
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'dana_account' => 'required|string|max:20',
            'sponsor_username' => 'required|string|exists:users,username,role,member',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'provinsi_id' => 'required|exists:provinces,id_provinsi',
            'kab_kota_id' => 'required|exists:regencies,id_kab_kota',
            'kecamatan_id' => 'required|exists:districts,id_kecamatan',
            'desa_id' => 'required|exists:villages,id_desa',
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
            $marketingPin = MarketingPin::where('pin_code', $pinCode)
                ->where('is_used', false)
                ->where('valid_until', '>', now())
                ->first();

            if ($marketingPin) {
                $registrationType = 'marketing';
                $pinUsedId = $marketingPin->id_marketing_pins;
                $sponsorUser = User::where('username', $sponsorUsername)->first();
                if (!$sponsorUser) {
                    throw new \Exception('Sponsor tidak valid.');
                }
            } else {
                $sponsorUser = User::where('username', $sponsorUsername)->first();
                $sponsorPin = Pin::where('user_id', $sponsorUser->id_users)->first();

                if (!$sponsorPin || $sponsorPin->balance < 1) {
                    throw new \Exception('PIN Normal tidak valid atau saldo sponsor tidak mencukupi.');
                }

                $registrationType = 'normal';
                $pinUsedId = null;
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'member',
                'dana_account' => $request->dana_account,
                'is_active' => true,
            ]);

            $profile = MemberProfile::create([
                'user_id' => $user->id_users,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'id_sponsor' => $sponsorUsername,
                'id_upline' => null, // Diisi nanti oleh logika sistem matahari
                'provinsi_id' => $request->provinsi_id,
                'kab_kota_id' => $request->kab_kota_id,
                'kecamatan_id' => $request->kecamatan_id,
                'desa_id' => $request->desa_id,
            ]);

            $registration = Registration::create([
                'new_member_id' => $user->id_users,
                'sponsor_username' => $sponsorUsername,
                'upline_username' => null, // Diisi nanti oleh logika sistem matahari
                'registration_type' => $registrationType,
                'marketing_pin_id' => $pinUsedId,
                'registration_fee' => $registrationType === 'normal' ? 100000 : null,
                'admin_fee' => $registrationType === 'normal' ? 15000 : null,
            ]);

            if ($registrationType === 'normal') {
                $sponsorPin->decrement('balance', 1);
            }

            if ($registrationType === 'marketing') {
                $marketingPin->update(['is_used' => true, 'assigned_user_id' => $user->id_users]);
            }

            // Logika Sistem Matahari di sini (lihat contoh di bawah)
            $this->applySunSystemLogic($registration, $sponsorUsername);

            $defaultProducts = \App\Models\DigitalProduct::where('access_level', 'all_members')->get();
            foreach ($defaultProducts as $product) {
                \App\Models\MemberProductAccess::create([
                    'user_id' => $user->id_users,
                    'product_id' => $product->id_digital_products,
                ]);
            }
        });

        if ($user && $profile) {
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

    // Contoh implementasi logika Sistem Matahari
    private function applySunSystemLogic($registration, $sponsorUsername)
    {
        $newMemberUsername = $registration->newMember->username;
        $sponsorUser = User::where('username', $sponsorUsername)->first();
        if (!$sponsorUser) return;

        // Dapatkan upline saat ini dari sponsor
        $currentSponsorUpline = $sponsorUser->profile->id_upline; // atau dari kolom lain jika lebih kompleks

        // Dapatkan sponsor level 1-4 dari upline saat ini (rekursif atau dari tabel struktur)
        $uplineChain = $this->getUplineChain($currentSponsorUpline, 4);

        // Update profil member baru
        $registration->newMember->profile()->update([
            'id_sponsor' => $sponsorUsername,
            'id_upline' => $currentSponsorUpline,
        ]);

        // Update record registrasi
        $registration->update([
            'sponsor_username' => $sponsorUsername,
            'upline_username' => $currentSponsorUpline,
        ]);

        // Hitung dan catat bonus untuk sponsor dan upline
        $bonusAmounts = [1 => 25000, 2 => 25000, 3 => 25000, 4 => 10000];
        foreach ($uplineChain as $level => $uplineUsername) {
            if ($uplineUsername && isset($bonusAmounts[$level])) {
                BonusCalculation::create([
                    'registration_id' => $registration->id_registrations,
                    'bonus_member_username' => $uplineUsername,
                    'level' => $level,
                    'amount' => $bonusAmounts[$level],
                    'calculation_date' => now()->toDateString(),
                    'status' => 'calculated',
                ]);
            }
        }
    }

    // Fungsi bantu untuk mendapatkan chain upline (implementasi sebenarnya bisa kompleks)
    private function getUplineChain($uplineUsername, $maxLevel, $currentLevel = 1, &$chain = [])
    {
        if ($currentLevel > $maxLevel || !$uplineUsername) {
            return $chain;
        }

        $chain[$currentLevel] = $uplineUsername;

        // Dapatkan upline dari username saat ini
        $uplineProfile = MemberProfile::where('user_id', function($query) use ($uplineUsername) {
            $query->select('id_users')->from('users')->where('username', $uplineUsername);
        })->first();

        $nextUpline = $uplineProfile ? $uplineProfile->id_upline : null;

        return $this->getUplineChain($nextUpline, $maxLevel, $currentLevel + 1, $chain);
    }
}

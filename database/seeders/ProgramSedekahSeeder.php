<?php

namespace Database\Seeders;

//use Database\Seeders\WilayahIndonesiaSeeder;
use App\Models\BonusCalculation;
use App\Models\DigitalProduct;
use App\Models\MarketingPin;
use App\Models\MemberProfile;
use App\Models\Pin;
use App\Models\Registration;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProgramSedekahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil seeder wilayah terlebih dahulu
        $this->call(WilayahIndonesiaSeeder::class);

        // 1. Buat Admin
        $admin = User::create([
            'username' => 'SED-ADMIN',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'dana_account' => '081234567890', // Contoh nomor HP
            'is_active' => true,
        ]);

        // 2. Buat Produk Digital Default
        $productAll = DigitalProduct::create([
            'title' => 'Panduan Awal',
            'description' => 'Panduan dasar untuk member baru.',
            'file_path' => 'public/products/guide.pdf', // Contoh path
            'file_type' => 'pdf',
            'access_level' => 'all_members',
            'created_by' => $admin->id_users,
        ]);

        // 3. Buat Member A (Level Tertinggi - Upline Awal)
        $memberA = User::create([
            'username' => 'SED-AAAAAA',
            'email' => 'memberA@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567891',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberA->id_users,
            'full_name' => 'Member A',
            'phone_number' => '081234567891',
            'address' => 'Alamat Member A',
            'id_sponsor' => null, // Member A tidak memiliki sponsor awal
            'id_upline' => null,  // Member A tidak memiliki upline awal
            'provinsi_id' => '31', // Jakarta
            'kab_kota_id' => '3175', // Kota Tangerang
            'kecamatan_id' => '3175010', // Kec. Pinang
            'desa_id' => '3175010001', // Kel. Pinang
        ]);

        Pin::create([
            'id_users' => $memberA->id_users,
            'balance' => 10, // Beri beberapa PIN Normal untuk transaksi
        ]);

        // 4. Buat Member B (Level 1 dari A)
        $memberB = User::create([
            'username' => 'SED-BBBBBB',
            'email' => 'memberB@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567892',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberB->id_users,
            'full_name' => 'Member B',
            'phone_number' => '081234567892',
            'address' => 'Alamat Member B',
            'id_sponsor' => $memberA->username, // Sponsor adalah A
            'id_upline' => $memberA->username,  // Upline adalah A (Level 1)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        // Registrasi B (Normal)
        $regB = Registration::create([
            'new_member_id' => $memberB->id_users,
            'sponsor_username' => $memberA->username,
            'upline_username' => $memberA->username, // Level 1
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A dari B
        BonusCalculation::create([
            'registration_id' => $regB->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated', // Status awal calculated
        ]);

        // 5. Buat Member C (Level 2 dari A, Level 1 dari B)
        $memberC = User::create([
            'username' => 'SED-CCCCCC',
            'email' => 'memberC@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567893',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberC->id_users,
            'full_name' => 'Member C',
            'phone_number' => '081234567893',
            'address' => 'Alamat Member C',
            'id_sponsor' => $memberB->username, // Sponsor adalah B
            'id_upline' => $memberA->username,  // Upline adalah A (Level 2)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regC = Registration::create([
            'new_member_id' => $memberC->id_users,
            'sponsor_username' => $memberB->username,
            'upline_username' => $memberA->username, // Level 2
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 2) dan B (Level 1) dari C
        BonusCalculation::create([
            'registration_id' => $regC->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regC->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 6. Buat Member D (Level 3 dari A, Level 2 dari B, Level 1 dari C)
        $memberD = User::create([
            'username' => 'SED-DDDDDD',
            'email' => 'memberD@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567894',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberD->id_users,
            'full_name' => 'Member D',
            'phone_number' => '081234567894',
            'address' => 'Alamat Member D',
            'id_sponsor' => $memberC->username, // Sponsor adalah C
            'id_upline' => $memberA->username,  // Upline adalah A (Level 3)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regD = Registration::create([
            'new_member_id' => $memberD->id_users,
            'sponsor_username' => $memberC->username,
            'upline_username' => $memberA->username, // Level 3
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 3), B (Level 2), dan C (Level 1) dari D
        BonusCalculation::create([
            'registration_id' => $regD->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 3, // Level 3
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regD->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regD->id_registrations,
            'bonus_member_username' => $memberC->username, // C menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 7. Buat Member E (Level 4 dari A, Level 3 dari B, Level 2 dari C, Level 1 dari D)
        $memberE = User::create([
            'username' => 'SED-EEEEEE',
            'email' => 'memberE@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567895',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberE->id_users,
            'full_name' => 'Member E',
            'phone_number' => '081234567895',
            'address' => 'Alamat Member E',
            'id_sponsor' => $memberD->username, // Sponsor adalah D
            'id_upline' => $memberA->username,  // Upline adalah A (Level 4)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regE = Registration::create([
            'new_member_id' => $memberE->id_users,
            'sponsor_username' => $memberD->username,
            'upline_username' => $memberA->username, // Level 4
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 4), B (Level 3), C (Level 2), dan D (Level 1) dari E
        BonusCalculation::create([
            'registration_id' => $regE->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 4, // Level 4
            'amount' => 10000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regE->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 3, // Level 3
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regE->id_registrations,
            'bonus_member_username' => $memberC->username, // C menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regE->id_registrations,
            'bonus_member_username' => $memberD->username, // D menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 8. Buat Member F (Level 1 dari A - Baris baru)
        $memberF = User::create([
            'username' => 'SED-FFFFFF',
            'email' => 'memberF@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567896',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberF->id_users,
            'full_name' => 'Member F',
            'phone_number' => '081234567896',
            'address' => 'Alamat Member F',
            'id_sponsor' => $memberA->username, // Sponsor adalah A
            'id_upline' => $memberA->username,  // Upline adalah A (Level 1)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regF = Registration::create([
            'new_member_id' => $memberF->id_users,
            'sponsor_username' => $memberA->username,
            'upline_username' => $memberA->username, // Level 1
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A dari F
        BonusCalculation::create([
            'registration_id' => $regF->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 9. Buat Member G (Level 1 dari A - Baris baru lagi)
        $memberG = User::create([
            'username' => 'SED-GGGGGG',
            'email' => 'memberG@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567897',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberG->id_users,
            'full_name' => 'Member G',
            'phone_number' => '081234567897',
            'address' => 'Alamat Member G',
            'id_sponsor' => $memberA->username, // Sponsor adalah A
            'id_upline' => $memberA->username,  // Upline adalah A (Level 1)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regG = Registration::create([
            'new_member_id' => $memberG->id_users,
            'sponsor_username' => $memberA->username,
            'upline_username' => $memberA->username, // Level 1
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A dari G
        BonusCalculation::create([
            'registration_id' => $regG->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 10. Buat Member H (Level 1 dari B - Baris baru dari B)
        $memberH = User::create([
            'username' => 'SED-HHHHHH',
            'email' => 'memberH@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567898',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberH->id_users,
            'full_name' => 'Member H',
            'phone_number' => '081234567898',
            'address' => 'Alamat Member H',
            'id_sponsor' => $memberB->username, // Sponsor adalah B
            'id_upline' => $memberA->username,  // Upline adalah A (Level 2)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regH = Registration::create([
            'new_member_id' => $memberH->id_users,
            'sponsor_username' => $memberB->username,
            'upline_username' => $memberA->username, // Level 2
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 2) dan B (Level 1) dari H
        BonusCalculation::create([
            'registration_id' => $regH->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regH->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 11. Buat Member I (Level 1 dari C - Baris baru dari C)
        $memberI = User::create([
            'username' => 'SED-IIIIII',
            'email' => 'memberI@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567899',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberI->id_users,
            'full_name' => 'Member I',
            'phone_number' => '081234567899',
            'address' => 'Alamat Member I',
            'id_sponsor' => $memberC->username, // Sponsor adalah C
            'id_upline' => $memberA->username,  // Upline adalah A (Level 3)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regI = Registration::create([
            'new_member_id' => $memberI->id_users,
            'sponsor_username' => $memberC->username,
            'upline_username' => $memberA->username, // Level 3
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 3), B (Level 2), dan C (Level 1) dari I
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 3, // Level 3
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberC->username, // C menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 12. Buat Member J (Level 1 dari D - Baris baru dari D)
        $memberJ = User::create([
            'username' => 'SED-JJJJJJ',
            'email' => 'memberJ@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567810',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberJ->id_users,
            'full_name' => 'Member J',
            'phone_number' => '081234567810',
            'address' => 'Alamat Member J',
            'id_sponsor' => $memberD->username, // Sponsor adalah D
            'id_upline' => $memberA->username,  // Upline adalah A (Level 4)
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        $regJ = Registration::create([
            'new_member_id' => $memberJ->id_users,
            'sponsor_username' => $memberD->username,
            'upline_username' => $memberA->username, // Level 4
            'registration_type' => 'normal',
            'marketing_pin_id' => null,
            'registration_fee' => 100000,
            'admin_fee' => 15000,
        ]);

        // Hitung Bonus untuk A (Level 4), B (Level 3), C (Level 2), dan D (Level 1) dari J
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberA->username, // A menerima bonus
            'level' => 4, // Level 4
            'amount' => 10000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberB->username, // B menerima bonus
            'level' => 3, // Level 3
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberC->username, // C menerima bonus
            'level' => 2, // Level 2
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberD->username, // D menerima bonus
            'level' => 1, // Level 1
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 13. Buat Member K (Mendaftar dengan PIN Marketing)
        $memberK = User::create([
            'username' => 'SED-KKKKKK',
            'email' => 'memberK@example.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'dana_account' => '081234567811',
            'is_active' => true,
        ]);

        MemberProfile::create([
            'id_users' => $memberK->id_users,
            'full_name' => 'Member K',
            'phone_number' => '081234567811',
            'address' => 'Alamat Member K',
            'id_sponsor' => $memberA->username, // Sponsor bisa diisi, tapi tidak menghasilkan bonus
            'id_upline' => null,  // Upline adalah null karena pendaftaran marketing
            'provinsi_id' => '31',
            'kab_kota_id' => '3175',
            'kecamatan_id' => '3175010',
            'desa_id' => '3175010001',
        ]);

        // Buat PIN Marketing untuk K
        $marketingPinK = MarketingPin::create([
            'pin_code' => 'MK' . strtoupper(Str::random(6)),
            'admin_issuer_id' => $admin->id_users,
            'assigned_id_users' => $memberK->id_users,
            'valid_until' => now()->addWeeks(2),
            'is_used' => true, // Sudah digunakan untuk pendaftaran
        ]);

        // Registrasi K (Marketing)
        $regK = Registration::create([
            'new_member_id' => $memberK->id_users,
            'sponsor_username' => $memberA->username,
            'upline_username' => null, // Level 0 atau null untuk marketing
            'registration_type' => 'marketing',
            'marketing_pin_id' => $marketingPinK->id_marketing_pins,
            'registration_fee' => null,
            'admin_fee' => null,
        ]);

        // 14. Tambahkan beberapa bonus lagi untuk member A dan B untuk contoh total
        // Bonus dari member lain (asumsi join lain dalam hari yang sama)
        // Bonus Level 1
        BonusCalculation::create([
            'registration_id' => $regF->id_registrations,
            'bonus_member_username' => $memberA->username,
            'level' => 1,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regG->id_registrations,
            'bonus_member_username' => $memberA->username,
            'level' => 1,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regH->id_registrations,
            'bonus_member_username' => $memberB->username,
            'level' => 1,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberC->username,
            'level' => 1,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberD->username,
            'level' => 1,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // Bonus Level 2
        BonusCalculation::create([
            'registration_id' => $regH->id_registrations,
            'bonus_member_username' => $memberA->username,
            'level' => 2,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberB->username,
            'level' => 2,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberC->username,
            'level' => 2,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // Bonus Level 3
        BonusCalculation::create([
            'registration_id' => $regI->id_registrations,
            'bonus_member_username' => $memberA->username,
            'level' => 3,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberB->username,
            'level' => 3,
            'amount' => 25000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // Bonus Level 4
        BonusCalculation::create([
            'registration_id' => $regJ->id_registrations,
            'bonus_member_username' => $memberA->username,
            'level' => 4,
            'amount' => 10000,
            'calculation_date' => now()->toDateString(),
            'status' => 'calculated',
        ]);

        // 15. Buat beberapa permintaan withdraw untuk member A dan B
        // Asumsikan total bonus calculated untuk A mencukupi
        WithdrawalRequest::create([
            'id_users' => $memberA->id_users,
            'amount' => 100000, // Contoh jumlah
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        WithdrawalRequest::create([
            'id_users' => $memberB->id_users,
            'amount' => 75000, // Contoh jumlah
            'status' => 'approved',
            'requested_at' => now()->subMinutes(30),
            'processed_at' => now(),
        ]);

        // 16. Buat beberapa PIN Marketing untuk admin
        MarketingPin::create([
            'pin_code' => 'MK' . strtoupper(Str::random(6)),
            'admin_issuer_id' => $admin->id_users,
            'assigned_id_users' => $memberB->id_users, // Bisa diberikan ke member tertentu
            'valid_until' => now()->addWeeks(1),
            'is_used' => false,
        ]);

        MarketingPin::create([
            'pin_code' => 'MK' . strtoupper(Str::random(6)),
            'admin_issuer_id' => $admin->id_users,
            'assigned_id_users' => null, // Belum diberikan
            'valid_until' => now()->addWeeks(5),
            'is_used' => false,
        ]);

        // 17. Berikan akses produk ke semua member baru
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberB->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberC->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberD->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberE->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberF->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberG->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberH->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberI->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberJ->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);
        \App\Models\MemberProductAccess::create([
            'id_users' => $memberK->id_users,
            'product_id' => $productAll->id_digital_products,
        ]);

        // 18. Tambahkan beberapa log
        \App\Models\Log::create([
            'id_users' => $admin->id_users,
            'action' => 'login',
            'description' => 'Admin login',
            'ip_address' => '127.0.0.1',
            'timestamp' => now(),
        ]);
        \App\Models\Log::create([
            'id_users' => $memberA->id_users,
            'action' => 'login',
            'description' => 'Member A login',
            'ip_address' => '127.0.0.1',
            'timestamp' => now(),
        ]);
        \App\Models\Log::create([
            'id_users' => $memberB->id_users,
            'action' => 'register',
            'description' => 'Member B registered via SED-AAAAAA',
            'ip_address' => '127.0.0.1',
            'timestamp' => now(),
        ]);
        \App\Models\Log::create([
            'id_users' => null, // Log aktivitas sistem
            'action' => 'system_start',
            'description' => 'Seeder executed',
            'ip_address' => '127.0.0.1',
            'timestamp' => now(),
        ]);

        // 19. Simulasikan pembayaran bonus untuk member B (status withdraw menjadi paid)
        \App\Models\PaymentHistory::create([
            'id_users' => $memberB->id_users,
            'amount' => 75000,
            'type' => 'withdrawal',
            'reference_id' => WithdrawalRequest::where('id_users', $memberB->id_users)->where('status', 'approved')->first()->id_withdrawal_requests,
            'payment_method' => 'Dana Manual',
            'payment_date' => now(),
        ]);

        // Update status withdrawal B menjadi paid
        WithdrawalRequest::where('id_users', $memberB->id_users)->where('status', 'approved')->first()->update(['status' => 'paid', 'paid_at' => now()]);

        // Update status bonus yang terkait menjadi paid (simulasi)
        // BonusCalculation::where('bonus_member_username', $memberB->username)->where('status', 'calculated')->limit(3)->update(['status' => 'paid']); // Contoh logika kompleks
    }
}

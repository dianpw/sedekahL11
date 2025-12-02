<?php

namespace App\Http\Controllers;

use App\Models\BonusCalculation;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'member') {
            abort(403, 'Unauthorized');
        }

        $bonusCalculations = BonusCalculation::where('bonus_member_username', $user->username)
            ->orderBy('calculation_date', 'desc')
            ->get();

        $pendingWithdrawal = WithdrawalRequest::where('user_id', $user->id_users)
            ->where('status', 'pending')
            ->first();

        $totalCalculatedBonus = $bonusCalculations->where('status', 'calculated')->sum('amount');
        $totalPaidBonus = $bonusCalculations->where('status', 'paid')->sum('amount');
        $totalAvailableBonus = $totalCalculatedBonus;

        // Contoh data untuk menu penghasilan (dari dokumen)
        $incomeData = [
            'level_1' => ['member' => 10, 'omset' => 250000],
            'level_2' => ['member' => 20, 'omset' => 500000],
            'level_3' => ['member' => 30, 'omset' => 750000],
            'level_4' => ['member' => 100, 'omset' => 1000000],
        ];

        return view('member.dashboard', compact(
            'user',
            'bonusCalculations',
            'pendingWithdrawal',
            'totalCalculatedBonus',
            'totalPaidBonus',
            'totalAvailableBonus',
            'incomeData'
        ));
    }

    public function requestWithdraw(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'member') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'amount' => 'required|numeric|min:50000',
        ]);

        $totalAvailableBonus = BonusCalculation::where('bonus_member_username', $user->username)
            ->where('status', 'calculated')
            ->sum('amount');

        if ($request->amount > $totalAvailableBonus) {
            return back()->withErrors(['amount' => 'Jumlah penarikan melebihi bonus yang tersedia.']);
        }

        WithdrawalRequest::create([
            'user_id' => $user->id_users,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->route('member.dashboard')->with('success', 'Permintaan penarikan berhasil diajukan.');
    }
}

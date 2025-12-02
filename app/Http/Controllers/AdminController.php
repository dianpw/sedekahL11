<?php

namespace App\Http\Controllers;

use App\Models\BonusCalculation;
use App\Models\MarketingPin;
use App\Models\Registration;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $totalMembers = User::where('role', 'member')->count();
        $totalRegistrations = Registration::count();
        $totalPendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
        $totalCalculatedBonuses = BonusCalculation::where('status', 'calculated')->sum('amount');
        $totalPaidBonuses = BonusCalculation::where('status', 'paid')->sum('amount');
        $totalAdminFees = Registration::where('registration_type', 'normal')->sum('admin_fee');

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalRegistrations',
            'totalPendingWithdrawals',
            'totalCalculatedBonuses',
            'totalPaidBonuses',
            'totalAdminFees'
        ));
    }

    public function manageMarketingPins()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $marketingPins = MarketingPin::with(['issuer', 'assignee'])->orderBy('created_at', 'desc')->get();
        return view('admin.marketing-pins', compact('marketingPins'));
    }

    public function createMarketingPin(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'validity_weeks' => 'required|integer|in:1,2,5',
            'assigned_to' => 'nullable|exists:users,username,role,member',
        ]);

        $validUntil = now()->addWeeks($request->validity_weeks);
        $pinCode = 'MK' . strtoupper(Str::random(6));

        MarketingPin::create([
            'pin_code' => $pinCode,
            'admin_issuer_id' => $user->id_users,
            'assigned_user_id' => $request->assigned_to,
            'valid_until' => $validUntil,
            'is_used' => false,
        ]);

        return redirect()->route('admin.marketing-pins')->with('success', 'PIN Marketing berhasil dibuat.');
    }

    public function manageWithdrawals()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $withdrawals = WithdrawalRequest::with('user')->orderBy('requested_at', 'desc')->get();
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function confirmWithdrawal(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $withdrawal = WithdrawalRequest::findOrFail($id);

        if ($request->action === 'approve') {
            $withdrawal->update([
                'status' => 'approved',
                'processed_at' => now(),
            ]);
        } elseif ($request->action === 'reject') {
            $withdrawal->update([
                'status' => 'rejected',
                'processed_at' => now(),
            ]);
        } elseif ($request->action === 'paid') {
            $withdrawal->update([
                'status' => 'paid',
                'paid_at' => now(),
                'processed_at' => now(),
            ]);
            \App\Models\PaymentHistory::create([
                'user_id' => $withdrawal->user_id,
                'amount' => $withdrawal->amount,
                'type' => 'withdrawal',
                'reference_id' => $withdrawal->id_withdrawal_requests,
                'payment_method' => 'Dana Manual',
                'payment_date' => now(),
            ]);
        }

        return redirect()->route('admin.withdrawals')->with('success', 'Status withdrawal berhasil diperbarui.');
    }
}

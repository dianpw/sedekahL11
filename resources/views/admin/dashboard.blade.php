@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Admin - {{ Auth::user()->username }}</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Total Member</h5>
                                    <h2>{{ $totalMembers }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Total Registrasi</h5>
                                    <h2>{{ $totalRegistrations }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5>Withdraw Pending</h5>
                                    <h2>{{ $totalPendingWithdrawals }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Total Admin Fee</h5>
                                    <h2>Rp {{ number_format($totalAdminFees, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Ringkasan Bonus</h5>
                            <p><strong>Total Bonus Dihitung:</strong> Rp {{ number_format($totalCalculatedBonuses, 0, ',', '.') }}</p>
                            <p><strong>Total Bonus Dibayarkan:</strong> Rp {{ number_format($totalPaidBonuses, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Aksi Cepat</h5>
                            <a href="{{ route('admin.marketing-pins') }}" class="btn btn-secondary me-2">Kelola PIN Marketing</a>
                            <a href="{{ route('admin.withdrawals') }}" class="btn btn-warning">Konfirmasi Withdrawal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Member - {{ Auth::user()->username }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Profil Saya</h5>
                            <p><strong>Nama:</strong> {{ $user->profile->full_name }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Akun Dana:</strong> {{ $user->dana_account }}</p>
                            <p><strong>Sponsor:</strong> {{ $user->profile->id_sponsor ?? 'N/A' }}</p>
                            <p><strong>Upline:</strong> {{ $user->profile->id_upline ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Saldo Bonus</h5>
                            <p><strong>Total Bonus Tersedia:</strong> Rp {{ number_format($totalAvailableBonus, 0, ',', '.') }}</p>
                            <p><strong>Total Bonus Dibayarkan:</strong> Rp {{ number_format($totalPaidBonus, 0, ',', '.') }}</p>
                            @if($pendingWithdrawal)
                                <p class="text-warning"><strong>Permintaan Withdraw Pending:</strong> Rp {{ number_format($pendingWithdrawal->amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Menu Penghasilan</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Member</th>
                                        <th>Omset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incomeData as $level => $data)
                                        <tr>
                                            <td>{{ strtoupper($level) }}</td>
                                            <td>{{ $data['member'] }}</td>
                                            <td>Rp {{ number_format($data['omset'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Permintaan Penarikan</h5>
                            <form method="POST" action="{{ route('member.withdraw') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah Penarikan (Min Rp 50.000)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="50000" value="{{ old('amount') }}" required>
                                    <small class="text-muted">Saldo bonus tersedia: Rp {{ number_format($totalAvailableBonus, 0, ',', '.') }}</small>
                                </div>
                                <button type="submit" class="btn btn-primary" {{ $totalAvailableBonus < 50000 ? 'disabled' : '' }}>Ajukan Withdraw</button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Riwayat Bonus</h5>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Level</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bonusCalculations as $bonus)
                                        <tr>
                                            <td>{{ $bonus->calculation_date }}</td>
                                            <td>{{ $bonus->level }}</td>
                                            <td>Rp {{ number_format($bonus->amount, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-{{ $bonus->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($bonus->status) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Tidak ada riwayat bonus.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

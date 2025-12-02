@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Kelola Withdrawal</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Member</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Diminta</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id_withdrawal_requests }}</td>
                                    <td>{{ $withdrawal->user->username }} ({{ $withdrawal->user->email }})</td>
                                    <td>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $withdrawal->status === 'pending' ? 'warning' : ($withdrawal->status === 'paid' ? 'success' : 'secondary') }}">{{ ucfirst($withdrawal->status) }}</span></td>
                                    <td>{{ $withdrawal->requested_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('admin.withdrawals.confirm', $withdrawal->id_withdrawal_requests) }}" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.withdrawals.confirm', $withdrawal->id_withdrawal_requests) }}" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.withdrawals.confirm', $withdrawal->id_withdrawal_requests) }}" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="action" value="paid">
                                                <button type="submit" class="btn btn-sm btn-primary">Bayar</button>
                                            </form>
                                        @elseif($withdrawal->status === 'approved')
                                            <form method="POST" action="{{ route('admin.withdrawals.confirm', $withdrawal->id_withdrawal_requests) }}" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="action" value="paid">
                                                <button type="submit" class="btn btn-sm btn-primary">Bayar Sekarang</button>
                                            </form>
                                        @else
                                            <!-- Tidak ada aksi untuk status selain pending/approved -->
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada permintaan withdrawal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

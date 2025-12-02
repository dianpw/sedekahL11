@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manajemen PIN Marketing</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.marketing-pins.create') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="validity_weeks" class="col-md-4 col-form-label text-md-end">Durasi (minggu)</label>
                            <div class="col-md-6">
                                <select id="validity_weeks" class="form-select" name="validity_weeks" required>
                                    <option value="1">1 Minggu</option>
                                    <option value="2">2 Minggu</option>
                                    <option value="5">5 Minggu</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="assigned_to" class="col-md-4 col-form-label text-md-end">Berikan Ke (Opsional)</label>
                            <div class="col-md-6">
                                <select id="assigned_to" class="form-select" name="assigned_to">
                                    <option value="">Pilih Member</option>
                                    @foreach(\App\Models\User::where('role', 'member')->get() as $member)
                                        <option value="{{ $member->username }}">{{ $member->username }} ({{ $member->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Buat PIN Marketing</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <h5>Daftar PIN Marketing</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode PIN</th>
                                <th>Penerbit</th>
                                <th>Penerima</th>
                                <th>Kadaluarsa</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($marketingPins as $pin)
                                <tr>
                                    <td>{{ $pin->pin_code }}</td>
                                    <td>{{ $pin->issuer->username ?? 'N/A' }}</td>
                                    <td>{{ $pin->assignee->username ?? 'Belum Diberikan' }}</td>
                                    <td>{{ $pin->valid_until->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($pin->is_used)
                                            <span class="badge bg-danger">Digunakan</span>
                                        @elseif($pin->valid_until < now())
                                            <span class="badge bg-secondary">Kadaluarsa</span>
                                        @else
                                            <span class="badge bg-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $pin->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada PIN Marketing.</td>
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

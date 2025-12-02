@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username (SED-******)') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="SED-ABC123">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dana_account" class="col-md-4 col-form-label text-md-end">{{ __('Akun Dana (Nomor HP)') }}</label>
                            <div class="col-md-6">
                                <input id="dana_account" type="text" class="form-control @error('dana_account') is-invalid @enderror" name="dana_account" value="{{ old('dana_account') }}" required autocomplete="dana_account" placeholder="081234567890">
                                @error('dana_account')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="sponsor_username" class="col-md-4 col-form-label text-md-end">{{ __('Username Sponsor') }}</label>
                            <div class="col-md-6">
                                <input id="sponsor_username" type="text" class="form-control @error('sponsor_username') is-invalid @enderror" name="sponsor_username" value="{{ old('sponsor_username') }}" required autocomplete="sponsor_username" placeholder="SED-XYZ789">
                                @error('sponsor_username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="full_name" class="col-md-4 col-form-label text-md-end">{{ __('Nama Lengkap') }}</label>
                            <div class="col-md-6">
                                <input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required autocomplete="full_name">
                                @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-end">{{ __('Nomor HP') }}</label>
                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone_number" placeholder="081234567890">
                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Detail') }}</label>
                            <div class="col-md-6">
                                <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" autocomplete="address">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="provinsi_id" class="col-md-4 col-form-label text-md-end">{{ __('Provinsi') }}</label>
                            <div class="col-md-6">
                                <select id="provinsi_id" class="form-select @error('provinsi_id') is-invalid @enderror" name="provinsi_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id_provinsi }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('provinsi_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kab_kota_id" class="col-md-4 col-form-label text-md-end">{{ __('Kabupaten / Kota') }}</label>
                            <div class="col-md-6">
                                <select id="kab_kota_id" class="form-select @error('kab_kota_id') is-invalid @enderror" name="kab_kota_id" required>
                                    <option value="">Pilih Kabupaten / Kota</option>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </select>
                                @error('kab_kota_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kecamatan_id" class="col-md-4 col-form-label text-md-end">{{ __('Kecamatan') }}</label>
                            <div class="col-md-6">
                                <select id="kecamatan_id" class="form-select @error('kecamatan_id') is-invalid @enderror" name="kecamatan_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </select>
                                @error('kecamatan_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="desa_id" class="col-md-4 col-form-label text-md-end">{{ __('Desa / Kelurahan') }}</label>
                            <div class="col-md-6">
                                <select id="desa_id" class="form-select @error('desa_id') is-invalid @enderror" name="desa_id" required>
                                    <option value="">Pilih Desa / Kelurahan</option>
                                    <!-- Data akan diisi oleh JavaScript -->
                                </select>
                                @error('desa_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="pin_code" class="col-md-4 col-form-label text-md-end">{{ __('Kode PIN') }}</label>
                            <div class="col-md-6">
                                <input id="pin_code" type="text" class="form-control @error('pin_code') is-invalid @enderror" name="pin_code" value="{{ old('pin_code') }}" required autocomplete="pin_code" placeholder="Kode PIN Normal atau Marketing">
                                @error('pin_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinsiSelect = document.getElementById('provinsi_id');
    const kabKotaSelect = document.getElementById('kab_kota_id');
    const kecamatanSelect = document.getElementById('kecamatan_id');
    const desaSelect = document.getElementById('desa_id');

    provinsiSelect.addEventListener('change', function () {
        const provinsiId = this.value;
        kabKotaSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        desaSelect.innerHTML = '<option value="">Pilih Desa / Kelurahan</option>';

        if (provinsiId) {
            fetch(`/api/regencies/${provinsiId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kabKota => {
                        const option = document.createElement('option');
                        option.value = kabKota.id_kab_kota;
                        option.textContent = kabKota.name;
                        kabKotaSelect.appendChild(option);
                    });
                });
        }
    });

    kabKotaSelect.addEventListener('change', function () {
        const kabKotaId = this.value;
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        desaSelect.innerHTML = '<option value="">Pilih Desa / Kelurahan</option>';

        if (kabKotaId) {
            fetch(`/api/districts/${kabKotaId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kecamatan => {
                        const option = document.createElement('option');
                        option.value = kecamatan.id_kecamatan;
                        option.textContent = kecamatan.name;
                        kecamatanSelect.appendChild(option);
                    });
                });
        }
    });

    kecamatanSelect.addEventListener('change', function () {
        const kecamatanId = this.value;
        desaSelect.innerHTML = '<option value="">Pilih Desa / Kelurahan</option>';

        if (kecamatanId) {
            fetch(`/api/villages/${kecamatanId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(desa => {
                        const option = document.createElement('option');
                        option.value = desa.id_desa;
                        option.textContent = desa.name;
                        desaSelect.appendChild(option);
                    });
                });
        }
    });
});
</script>
@endsection

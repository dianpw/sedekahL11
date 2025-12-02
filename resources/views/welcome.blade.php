@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Selamat Datang') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Ini adalah halaman utama Program Sedekah.</p>
                    <p>Gunakan menu di atas untuk Login atau Register.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

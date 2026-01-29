@extends('layout.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="display-1">404</h1>
            <h2>Halaman Tidak Ditemukan</h2>
            <p class="lead">{{ $message ?? 'Halaman yang Anda cari tidak ditemukan.' }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

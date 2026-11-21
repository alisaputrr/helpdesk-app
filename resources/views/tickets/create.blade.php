@extends('layouts.app')

@section('title', 'Buat Tiket')
@section('heading', 'Buat Tiket')

@section('content')
    <div class="page-head">
        <div>
            <h1>Buat Tiket Baru</h1>
            <p>Catat laporan gangguan agar bisa segera ditangani.</p>
        </div>
    </div>

    <form class="card" method="POST" action="{{ route('tickets.store') }}">
        @csrf
        @include('tickets._form', ['ticket' => null])
    </form>
@endsection

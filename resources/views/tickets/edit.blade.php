@extends('layouts.app')

@section('title', 'Edit Tiket')
@section('heading', 'Edit Tiket')

@section('content')
    <div class="page-head">
        <div>
            <span class="kode">{{ $ticket->kode }}</span>
            <h1>Edit Tiket</h1>
            <p>Perbarui data atau status penanganan tiket.</p>
        </div>
    </div>

    <form class="card" method="POST" action="{{ route('tickets.update', $ticket->id) }}">
        @csrf
        @method('PUT')
        @include('tickets._form')
    </form>
@endsection

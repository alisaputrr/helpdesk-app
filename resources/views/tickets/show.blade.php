@extends('layouts.app')

@section('title', 'Detail Tiket')
@section('heading', 'Detail Tiket')

@section('content')
    <div class="page-head">
        <div>
            <span class="kode">{{ $ticket->kode }}</span>
            <h1>{{ $ticket->judul }}</h1>
            <p>
                <span class="badge badge-{{ strtolower($ticket->status) }}">{{ $ticket->status }}</span>
                <span class="badge prio-{{ strtolower($ticket->prioritas) }}">{{ $ticket->prioritas }}</span>
            </p>
        </div>
        <div class="actions">
            <a href="{{ route('tickets.index') }}" class="btn">← Semua Tiket</a>
            <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="card">
        <dl class="detail">
            <div class="full"><dt>Deskripsi</dt><dd>{{ $ticket->deskripsi }}</dd></div>
            <div><dt>Kategori</dt><dd>{{ $ticket->kategori }}</dd></div>
            <div><dt>Lokasi</dt><dd>{{ $ticket->lokasi ?: '-' }}</dd></div>
            <div><dt>Pelapor</dt><dd>{{ $ticket->pelapor }}</dd></div>
            <div><dt>Email pelapor</dt><dd>{{ $ticket->email_pelapor ?: '-' }}</dd></div>
            <div><dt>Petugas penanggung jawab</dt><dd>{{ $ticket->petugas ?: 'Belum ditugaskan' }}</dd></div>
            <div>
                <dt>Target penyelesaian</dt>
                <dd>{{ $ticket->target_selesai ? \Carbon\Carbon::parse($ticket->target_selesai)->format('d M Y') : '-' }}</dd>
            </div>
            <div><dt>Dibuat</dt><dd>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</dd></div>
            <div><dt>Diperbarui</dt><dd>{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d M Y, H:i') }}</dd></div>
        </dl>
    </div>
@endsection

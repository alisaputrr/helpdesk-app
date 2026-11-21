@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Ringkasan layanan</span>
            <h1>Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p>Pantau kondisi tiket dan pekerjaan yang perlu diprioritaskan hari ini.</p>
        </div>
        <div class="actions">
            <a href="{{ route('tickets.index') }}" class="btn">Lihat Semua Tiket</a>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">+ Buat Tiket</a>
        </div>
    </div>

    <div class="stats">
        <div class="card stat">
            <div class="label">Total tiket <span class="icon">▤</span></div>
            <div class="num">{{ $total }}</div>
            <div class="note">Seluruh laporan yang tercatat</div>
        </div>
        <div class="card stat warn">
            <div class="label">Perlu ditangani <span class="icon">!</span></div>
            <div class="num">{{ $perluDitangani }}</div>
            <div class="note">{{ $baru }} baru · {{ $diproses }} sedang diproses</div>
        </div>
        <div class="card stat ok">
            <div class="label">Tiket selesai <span class="icon">✓</span></div>
            <div class="num">{{ $selesai }}</div>
            <div class="note">Tingkat penyelesaian {{ $rate }}%</div>
        </div>
        <div class="card stat hot">
            <div class="label">Butuh perhatian <span class="icon">♥</span></div>
            <div class="num">{{ $perhatian }}</div>
            <div class="note">{{ $tinggi }} prioritas tinggi · {{ $terlambat }} terlambat</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="page-head" style="margin-bottom:0">
                <div>
                    <h3>Tiket terbaru</h3>
                    <p class="sub">Enam laporan yang terakhir masuk</p>
                </div>
                <a href="{{ route('tickets.index') }}">Tampilkan semua →</a>
            </div>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr><th>Tiket</th><th>Kategori</th><th>Status</th><th>Prioritas</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($recent as $t)
                            <tr>
                                <td>
                                    <a href="{{ route('tickets.show', $t->id) }}" class="judul">{{ $t->judul }}</a>
                                    <span class="kode">{{ $t->kode }}</span>
                                </td>
                                <td>{{ $t->kategori }}</td>
                                <td><span class="badge badge-{{ strtolower($t->status) }}">{{ $t->status }}</span></td>
                                <td><span class="badge prio-{{ strtolower($t->prioritas) }}">{{ $t->prioritas }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty">Belum ada tiket.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3>Distribusi status</h3>
            <p class="sub">Komposisi seluruh tiket</p>
            <div class="dist">
                @foreach ($byStatus as $row)
                    <div class="dist-row">
                        <div class="top">{{ $row['status'] }} <span>{{ $row['total'] }} tiket · {{ $row['persen'] }}%</span></div>
                        <div class="bar"><i class="f-{{ strtolower($row['status']) }}" style="width: {{ $row['persen'] }}%"></i></div>
                    </div>
                @endforeach
            </div>
            <div class="rate">
                <b>{{ $rate }}%</b>
                Tingkat penyelesaian keseluruhan
            </div>
        </div>
    </div>
@endsection

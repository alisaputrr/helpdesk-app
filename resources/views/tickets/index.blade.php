@extends('layouts.app')

@section('title', 'Semua Tiket')
@section('heading', 'Manajemen Tiket')

@section('content')
    <div class="page-head">
        <div>
            <h1>Semua Tiket</h1>
            <p>Cari, saring, dan kelola laporan layanan TI kampus.</p>
        </div>
        <div class="actions">
            <a href="{{ route('tickets.trash') }}" class="btn">Tempat Sampah</a>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">+ Tiket Baru</a>
        </div>
    </div>

    <form class="card" method="GET" action="{{ route('tickets.index') }}">
        <div class="filters">
            <div>
                <label class="f" for="q">Pencarian</label>
                <input class="input" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Kode, judul, pelapor, atau lokasi">
            </div>
            <div>
                <label class="f" for="status">Status</label>
                <select class="input" id="status" name="status">
                    <option value="">Semua</option>
                    @foreach ($statusList as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="f" for="prioritas">Prioritas</label>
                <select class="input" id="prioritas" name="prioritas">
                    <option value="">Semua</option>
                    @foreach ($prioritasList as $p)
                        <option value="{{ $p }}" @selected(request('prioritas') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="f" for="kategori">Kategori</label>
                <select class="input" id="kategori" name="kategori">
                    <option value="">Semua</option>
                    @foreach ($kategoriList as $k)
                        <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="f" for="urut">Urutkan</label>
                <select class="input" id="urut" name="urut">
                    <option value="terbaru" @selected(request('urut', 'terbaru') === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(request('urut') === 'terlama')>Terlama</option>
                    <option value="prioritas" @selected(request('urut') === 'prioritas')>Prioritas tertinggi</option>
                    <option value="target" @selected(request('urut') === 'target')>Target terdekat</option>
                </select>
            </div>
            <a href="{{ route('tickets.index') }}" class="btn">Reset</a>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </div>
    </form>

    <div class="card">
        <h3>Daftar tiket</h3>
        <p class="sub">
            @if ($tickets->total() > 0)
                Menampilkan {{ $tickets->firstItem() }}-{{ $tickets->lastItem() }} dari {{ $tickets->total() }} tiket
            @else
                Tidak ada tiket yang sesuai
            @endif
        </p>

        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>Kode</th><th>Masalah</th><th>Pelapor</th><th>Status</th>
                        <th>Prioritas</th><th>Target</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $t)
                        @php
                            $late = $t->target_selesai
                                && $t->status !== 'Selesai'
                                && \Carbon\Carbon::parse($t->target_selesai)->lt(today());
                        @endphp
                        <tr>
                            <td><a class="kode" href="{{ route('tickets.show', $t->id) }}">{{ $t->kode }}</a></td>
                            <td>
                                <span class="judul">{{ $t->judul }}</span>
                                <span class="meta">{{ $t->kategori }}@if ($t->lokasi) · {{ $t->lokasi }}@endif</span>
                            </td>
                            <td>{{ $t->pelapor }}</td>
                            <td><span class="badge badge-{{ strtolower($t->status) }}">{{ $t->status }}</span></td>
                            <td><span class="badge prio-{{ strtolower($t->prioritas) }}">{{ $t->prioritas }}</span></td>
                            <td class="{{ $late ? 'overdue' : '' }}">
                                {{ $t->target_selesai ? \Carbon\Carbon::parse($t->target_selesai)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-sm" href="{{ route('tickets.show', $t->id) }}">Detail</a>
                                    <a class="btn btn-sm" href="{{ route('tickets.edit', $t->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('tickets.destroy', $t->id) }}"
                                          onsubmit="return confirm('Pindahkan tiket ini ke Tempat Sampah?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada tiket yang sesuai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tickets->hasPages())
            <div class="pager">
                <span>Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}</span>
                <div class="pages">
                    @if ($tickets->onFirstPage())
                        <span class="off">‹</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach (range(1, $tickets->lastPage()) as $page)
                        @if ($page === $tickets->currentPage())
                            <span class="on">{{ $page }}</span>
                        @else
                            <a href="{{ $tickets->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}">›</a>
                    @else
                        <span class="off">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Tempat Sampah')
@section('heading', 'Tempat Sampah')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Arsip terhapus</span>
            <h1>Tempat Sampah</h1>
            <p>Pulihkan tiket atau hapus permanen jika benar-benar tidak diperlukan.</p>
        </div>
        <div class="actions">
            <a href="{{ route('tickets.index') }}" class="btn">← Semua Tiket</a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr><th>Kode</th><th>Masalah</th><th>Pelapor</th><th>Dihapus</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $t)
                        <tr>
                            <td><span class="kode">{{ $t->kode }}</span></td>
                            <td>
                                <span class="judul">{{ $t->judul }}</span>
                                <span class="meta">{{ $t->kategori }}</span>
                            </td>
                            <td>{{ $t->pelapor }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->deleted_at)->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="row-actions">
                                    <form method="POST" action="{{ route('tickets.restore', $t->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm">Pulihkan</button>
                                    </form>
                                    <form method="POST" action="{{ route('tickets.force', $t->id) }}"
                                          onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus Permanen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Tempat sampah kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

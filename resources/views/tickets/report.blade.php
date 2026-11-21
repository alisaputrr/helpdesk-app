@extends('layouts.app')

@section('title', 'Laporan Tiket')
@section('heading', 'Laporan Tiket')

@section('content')
    <div class="page-head">
        <div>
            <h1>Laporan Tiket Helpdesk</h1>
            <p>Rekap jumlah tiket per status dan prioritas.</p>
        </div>
    </div>

    <form class="card" method="GET" action="{{ route('tickets.report') }}">
        <div class="filters" style="grid-template-columns: 1fr 1fr auto auto;">
            <div>
                <label class="f" for="from">Dari tanggal</label>
                <input class="input" type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}">
            </div>
            <div>
                <label class="f" for="to">Sampai tanggal</label>
                <input class="input" type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}">
            </div>
            <a href="{{ route('tickets.report') }}" class="btn">Reset</a>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </div>
    </form>

    @if ($errors->any())
        <div class="errors" style="margin-top:20px">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="stats" style="grid-template-columns: 1fr; margin-top:20px">
        <div class="card stat">
            <div class="label">Total tiket</div>
            <div class="num">{{ $totalTickets }}</div>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns: 1fr 1fr;">
        <div class="card">
            <h3>Jumlah per Status</h3>
            <table class="data" style="margin-top:12px">
                <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse ($byStatus as $row)
                        <tr>
                            <td><span class="badge badge-{{ strtolower($row->status) }}">{{ $row->status }}</span></td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="empty">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>Jumlah per Prioritas</h3>
            <table class="data" style="margin-top:12px">
                <thead><tr><th>Prioritas</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse ($byPriority as $row)
                        <tr>
                            <td><span class="badge prio-{{ strtolower($row->prioritas) }}">{{ $row->prioritas }}</span></td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="empty">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

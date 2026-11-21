<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    private const KATEGORI = ['Jaringan', 'Akun', 'Aplikasi', 'Perangkat', 'Lainnya'];
    private const PRIORITAS = ['Rendah', 'Sedang', 'Tinggi'];
    private const STATUS = ['Baru', 'Diproses', 'Menunggu', 'Selesai'];

    /* ---------- DAFTAR (pencarian, filter, urutan, pagination) ---------- */

    public function index(Request $request)
    {
        $query = DB::table('tickets')->whereNull('deleted_at');

        // Pencarian: OR dikelompokkan dalam closure supaya filter lain tidak rusak.
        if ($request->filled('q')) {
            $keyword = $request->input('q');

            $query->where(function ($sub) use ($keyword) {
                $sub->where('kode', 'like', "%{$keyword}%")
                    ->orWhere('judul', 'like', "%{$keyword}%")
                    ->orWhere('pelapor', 'like', "%{$keyword}%")
                    ->orWhere('lokasi', 'like', "%{$keyword}%");
            });
        }

        foreach (['status', 'prioritas', 'kategori'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        match ($request->input('urut', 'terbaru')) {
            'terlama' => $query->orderBy('created_at'),
            'prioritas' => $query
                ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
                ->orderByDesc('created_at'),
            'target' => $query->orderByRaw('target_selesai IS NULL')->orderBy('target_selesai'),
            default => $query->orderByDesc('created_at'),
        };

        $tickets = $query->paginate(10)->withQueryString();

        return view('tickets.index', [
            'tickets' => $tickets,
            'kategoriList' => self::KATEGORI,
            'prioritasList' => self::PRIORITAS,
            'statusList' => self::STATUS,
        ]);
    }

    /* ---------- BUAT ---------- */

    public function create()
    {
        return view('tickets.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        DB::table('tickets')->insert($validated + [
            'kode' => $this->newCode(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('tickets.index')
            ->with('pesan', 'Tiket berhasil dibuat.');
    }

    /* ---------- DETAIL & EDIT ---------- */

    public function show($id)
    {
        return view('tickets.show', ['ticket' => $this->findActive($id)]);
    }

    public function edit($id)
    {
        return view('tickets.edit', $this->formData() + ['ticket' => $this->findActive($id)]);
    }

    public function update(Request $request, $id)
    {
        $this->findActive($id);
        $validated = $request->validate($this->rules());

        // Selalu pakai where('id', ...) supaya hanya satu baris yang berubah.
        DB::table('tickets')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('tickets.show', $id)
            ->with('pesan', 'Tiket berhasil diperbarui.');
    }

    /* ---------- TEMPAT SAMPAH ---------- */

    // "Hapus" = isi deleted_at (soft delete manual), datanya tidak hilang.
    public function destroy($id)
    {
        $this->findActive($id);

        DB::table('tickets')->where('id', $id)->update(['deleted_at' => now()]);

        return redirect()
            ->route('tickets.index')
            ->with('pesan', 'Tiket dipindahkan ke Tempat Sampah.');
    }

    public function trash()
    {
        $tickets = DB::table('tickets')
            ->whereNotNull('deleted_at')
            ->orderByDesc('deleted_at')
            ->get();

        return view('tickets.trash', compact('tickets'));
    }

    public function restore($id)
    {
        DB::table('tickets')
            ->where('id', $id)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null, 'updated_at' => now()]);

        return redirect()->route('tickets.trash')->with('pesan', 'Tiket dipulihkan.');
    }

    public function forceDelete($id)
    {
        // Hanya baris yang sudah ada di tempat sampah yang boleh dihapus permanen.
        DB::table('tickets')
            ->where('id', $id)
            ->whereNotNull('deleted_at')
            ->delete();

        return redirect()->route('tickets.trash')->with('pesan', 'Tiket dihapus permanen.');
    }

    /* ---------- LAPORAN (praktikum Query Builder Lanjutan) ---------- */

    public function report(Request $request)
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $baseQuery = DB::table('tickets')->whereNull('deleted_at');

        if (!empty($filters['from']) && !empty($filters['to'])) {
            $baseQuery->whereBetween('created_at', [
                $filters['from'] . ' 00:00:00',
                $filters['to'] . ' 23:59:59',
            ]);
        }

        $totalTickets = (clone $baseQuery)->count();

        $byStatus = (clone $baseQuery)
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $byPriority = (clone $baseQuery)
            ->select('prioritas')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('prioritas')
            ->orderBy('prioritas')
            ->get();

        return view('tickets.report', compact(
            'totalTickets', 'byStatus', 'byPriority', 'filters'
        ));
    }

    /* ---------- HELPER ---------- */

    private function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', Rule::in(self::KATEGORI)],
            'prioritas' => ['required', Rule::in(self::PRIORITAS)],
            'status' => ['required', Rule::in(self::STATUS)],
            'target_selesai' => ['nullable', 'date'],
            'pelapor' => ['required', 'string', 'max:100'],
            'email_pelapor' => ['nullable', 'email', 'max:150'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'petugas' => ['nullable', 'string', 'max:100'],
        ];
    }

    private function formData(): array
    {
        return [
            'kategoriList' => self::KATEGORI,
            'prioritasList' => self::PRIORITAS,
            'statusList' => self::STATUS,
        ];
    }

    private function findActive($id)
    {
        $ticket = DB::table('tickets')->where('id', $id)->whereNull('deleted_at')->first();
        abort_if(!$ticket, 404);

        return $ticket;
    }

    private function newCode(): string
    {
        do {
            $kode = 'TKT-' . date('ymd') . '-' . strtoupper(Str::random(5));
        } while (DB::table('tickets')->where('kode', $kode)->exists());

        return $kode;
    }
}

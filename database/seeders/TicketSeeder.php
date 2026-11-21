<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * PERHATIAN: seeder ini MENGOSONGKAN tabel tickets lebih dulu,
 * lalu mengisi 25 tiket contoh dan membuat akun admin demo.
 * Hapus baris truncate() kalau data lama mau dipertahankan.
 */
class TicketSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@kampus.test'],
            [
                'name' => 'ALYSSA PUTRI',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('tickets')->truncate();

        mt_srand(7); // supaya hasil seeder selalu sama

        $templates = [
            ['Aplikasi presensi tidak dapat dibuka', 'Aplikasi', 'Fakultas Teknik'],
            ['Wi-Fi laboratorium tidak terhubung', 'Jaringan', 'Gedung Rektorat'],
            ['Proyektor ruang kelas tidak mendeteksi HDMI', 'Perangkat', 'Fakultas Teknik'],
            ['Akun sistem akademik terkunci', 'Akun', 'Gedung Rektorat'],
            ['Printer ruang administrasi bermasalah', 'Perangkat', 'Lab Komputer 1'],
            ['Komputer dosen berjalan sangat lambat', 'Perangkat', 'Gedung Rektorat'],
            ['DivLearning Kampus tidak bisa diakses', 'Jaringan', 'Perpustakaan'],
            ['Email kampus tidak bisa mengirim pesan', 'Akun', 'Ruang Dosen'],
            ['Kabel LAN ruang rapat putus', 'Jaringan', 'Gedung B'],
            ['Aplikasi keuangan error saat login', 'Aplikasi', 'Bagian Keuangan'],
        ];

        $pelapor = [
            'Salman', 'Hannah Putri', 'Aliya Kamilah', 'Syarifuddin', 'Joyce Herlambang',
            'Muhammad Owen', 'Andre Pakubumi', 'Rina Kartika', 'Budi Santoso', 'Citra Lestari',
            'Dedi Firmansyah', 'Eka Wulandari', 'Fajar Nugroho', 'Gita Ayu', 'Hendra Wijaya',
        ];

        // 6 Baru, 6 Diproses, 6 Menunggu, 7 Selesai
        $statuses = array_merge(
            array_fill(0, 6, 'Baru'),
            array_fill(0, 6, 'Diproses'),
            array_fill(0, 6, 'Menunggu'),
            array_fill(0, 7, 'Selesai'),
        );
        shuffle($statuses);

        $prioritas = ['Tinggi', 'Sedang', 'Rendah', 'Tinggi', 'Sedang'];

        $rows = [];
        foreach ($statuses as $i => $status) {
            $t = $templates[$i % count($templates)];
            $name = $pelapor[$i % count($pelapor)];
            $created = now()->subDays(25 - $i)->setTime(8 + ($i % 8), ($i * 7) % 60);

            $rows[] = [
                'kode' => 'TKT-' . date('ymd') . '-' . strtoupper(Str::random(5)),
                'pelapor' => $name,
                'judul' => $t[0],
                'deskripsi' => 'Mohon bantuan penanganan: ' . strtolower($t[0]) . '. Masalah dilaporkan oleh ' . $name . '.',
                'kategori' => $t[1],
                'prioritas' => $prioritas[$i % count($prioritas)],
                'status' => $status,
                'target_selesai' => $created->copy()->addDays(rand(3, 12))->toDateString(),
                'email_pelapor' => Str::slug($name, '.') . '@kampus.ac.id',
                'lokasi' => $t[2],
                'petugas' => $status === 'Baru' ? null : 'Teknisi ' . (($i % 3) + 1),
                'created_at' => $created,
                'updated_at' => $created,
                'deleted_at' => null,
            ];
        }

        DB::table('tickets')->insert($rows);
    }
}

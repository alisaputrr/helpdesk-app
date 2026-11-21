<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('kode', 30)->nullable()->unique()->after('id');
            $table->string('kategori', 50)->default('Lainnya')->after('deskripsi');
            $table->date('target_selesai')->nullable();
            $table->string('email_pelapor', 150)->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->string('petugas', 100)->nullable();
            // Query Builder tidak mengenal SoftDeletes, jadi kolom ini kita kelola manual.
            $table->timestamp('deleted_at')->nullable();
        });

        // Beri kode untuk tiket lama supaya tidak kosong.
        foreach (DB::table('tickets')->whereNull('kode')->pluck('id') as $id) {
            DB::table('tickets')->where('id', $id)->update([
                'kode' => 'TKT-' . date('ymd') . '-' . strtoupper(Str::random(5)),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['kode']);
            $table->dropColumn([
                'kode', 'kategori', 'target_selesai',
                'email_pelapor', 'lokasi', 'petugas', 'deleted_at',
            ]);
        });
    }
};

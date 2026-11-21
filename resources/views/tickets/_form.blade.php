@php
    // Ambil nilai lama (setelah validasi gagal), kalau tidak ada pakai data tiket.
    $val = fn ($key, $default = '') => old($key, $ticket->$key ?? $default);
@endphp

@if ($errors->any())
    <div class="errors">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="form-grid">
    <div class="full">
        <label class="f" for="judul">Judul gangguan *</label>
        <input class="input" type="text" id="judul" name="judul" value="{{ $val('judul') }}" placeholder="Contoh: Wi-Fi laboratorium tidak terhubung">
    </div>

    <div class="full">
        <label class="f" for="deskripsi">Deskripsi *</label>
        <textarea class="input" id="deskripsi" name="deskripsi" placeholder="Jelaskan masalah secara rinci">{{ $val('deskripsi') }}</textarea>
        <div class="hint">Semakin jelas deskripsi, semakin cepat petugas melakukan diagnosis.</div>
    </div>

    <div>
        <label class="f" for="kategori">Kategori *</label>
        <select class="input" id="kategori" name="kategori">
            @foreach ($kategoriList as $k)
                <option value="{{ $k }}" @selected($val('kategori', 'Jaringan') === $k)>{{ $k }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="f" for="prioritas">Prioritas *</label>
        <select class="input" id="prioritas" name="prioritas">
            @foreach ($prioritasList as $p)
                <option value="{{ $p }}" @selected($val('prioritas', 'Sedang') === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="f" for="status">Status *</label>
        <select class="input" id="status" name="status">
            @foreach ($statusList as $s)
                <option value="{{ $s }}" @selected($val('status', 'Baru') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="f" for="target_selesai">Target penyelesaian</label>
        <input class="input" type="date" id="target_selesai" name="target_selesai" value="{{ $val('target_selesai') }}">
    </div>

    <div>
        <label class="f" for="pelapor">Nama pelapor *</label>
        <input class="input" type="text" id="pelapor" name="pelapor" value="{{ $val('pelapor') }}" placeholder="Nama mahasiswa, dosen, atau staf">
    </div>

    <div>
        <label class="f" for="email_pelapor">Email pelapor</label>
        <input class="input" type="email" id="email_pelapor" name="email_pelapor" value="{{ $val('email_pelapor') }}" placeholder="nama@kampus.ac.id">
    </div>

    <div>
        <label class="f" for="lokasi">Lokasi</label>
        <input class="input" type="text" id="lokasi" name="lokasi" value="{{ $val('lokasi') }}" placeholder="Gedung, ruangan, atau laboratorium">
    </div>

    <div>
        <label class="f" for="petugas">Petugas penanggung jawab</label>
        <input class="input" type="text" id="petugas" name="petugas" value="{{ $val('petugas') }}" placeholder="Nama teknisi atau petugas">
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('tickets.index') }}" class="btn">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan Tiket</button>
</div>

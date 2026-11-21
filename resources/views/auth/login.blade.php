<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Helpdesk Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/helpdesk.css') }}">
</head>
<body>
<div class="login">
    <section class="login-hero">
        <div class="brand">
            <div class="brand-logo">H</div>
            <div><b>Helpdesk Kampus</b></div>
        </div>
        <span class="eyebrow" style="color:#ffc7de">Layanan TI terpadu</span>
        <h1>Kelola kendala teknologi dengan lebih cepat dan terukur.</h1>
        <p>Pantau laporan, tentukan prioritas, dan selesaikan tiket melalui satu ruang kerja yang terorganisasi.</p>
        <div class="big">24/7 <small>Pencatatan laporan</small></div>
    </section>

    <section class="login-side">
        <form class="login-card" method="POST" action="{{ route('login.process') }}">
            @csrf
            <span class="eyebrow">Selamat datang</span>
            <h2>Masuk ke ruang petugas</h2>
            <p class="sub">Gunakan akun yang telah diberikan oleh administrator.</p>

            @if ($errors->any())
                <div class="errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="field">
                <label class="f" for="email">Alamat email</label>
                <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field">
                <label class="f" for="password">Kata sandi</label>
                <input class="input" type="password" id="password" name="password" required>
            </div>

            <label class="check">
                <input type="checkbox" name="remember" value="1"> Ingat saya pada perangkat ini
            </label>

            <button type="submit" class="btn btn-primary">Masuk ke Dashboard</button>
            <p class="demo">Akun demo: admin@kampus.test · password</p>
        </form>
    </section>
</div>
</body>
</html>

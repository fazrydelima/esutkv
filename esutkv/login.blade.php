<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">
    <main class="login-layout">
        <section class="login-brand-panel">
            <div class="login-logos" aria-label="Logo eSUTKV dan Kolej Vokasional">
                <img src="{{ asset('image/logo esutkv.jpg') }}" alt="Logo eSUTKV">
                <img src="{{ asset('image/logo kv.jpg') }}" alt="Logo Kolej Vokasional">
            </div>
            <div class="login-brand-copy">
                <h1>Sistem Pengurusan Kejohanan Sukan</h1>
                <p>Urus acara, peserta dan kedudukan rumah sukan dalam satu sistem.</p>
            </div>
            <p class="login-footer-copy">E-SUTKV · Pengurusan Markah Kejohanan Sukan Tahunan</p>
        </section>

        <section class="login-form-panel">
            <div class="login-form-content">
                <p class="topbar-eyebrow">Selamat kembali</p>
                <h2>Log masuk</h2>
                <p>Masukkan maklumat akaun anda untuk meneruskan.</p>
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Emel</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Kata Laluan</label>
                        <input type="password" name="password" id="password" class="form-control"
                               autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Log Masuk ke Sistem</button>
                </form>
                <a class="d-block text-center mt-3" href="{{ route('scoreboard') }}">Lihat live scoreboard</a>
            </div>
        </section>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akaun Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Akaun Guru',
                'subtitle' => 'Tetapkan kata laluan empat akaun rumah sukan'
            ])
            <div class="page-content">
                <p class="page-intro">Tetapkan kata laluan untuk akaun guru. Kongsi butiran akaun hanya dengan guru rumah sukan berkaitan.</p>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    @foreach ($akaunGuru as $guru)
                        <div class="col-md-6">
                            <section class="card h-100">
                                <div class="card-header">{{ $guru->rumahSukan->nama_rumah ?? 'Rumah belum ditetapkan' }}</div>
                                <div class="card-body">
                                    <p><strong>Akaun:</strong> {{ $guru->email }}</p>
                                    <form action="{{ route('admin.guru-accounts.password', $guru) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="password-{{ $guru->id }}" class="form-label">Kata laluan baharu</label>
                                            <input id="password-{{ $guru->id }}" type="password" name="password"
                                                   class="form-control" minlength="12" autocomplete="new-password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password-confirmation-{{ $guru->id }}" class="form-label">Sahkan kata laluan</label>
                                            <input id="password-confirmation-{{ $guru->id }}" type="password" name="password_confirmation"
                                                   class="form-control" minlength="12" autocomplete="new-password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Tetapkan Kata Laluan</button>
                                    </form>
                                </div>
                            </section>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</body>
</html>

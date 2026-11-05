<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Senarai Peserta',
                'subtitle' => 'Pangkalan data atlet'
            ])
            <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-intro mb-0">
                <h2>Peserta berdaftar</h2>
                <p>Senarai peserta mengikut rumah sukan.</p>
            </div>
            <a href="{{ route('peserta.index') }}#form-tambah-peserta" class="btn btn-primary">
                Daftar Peserta
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="card">
            <div class="card-body">
                @if ($peserta->isEmpty())
                    <p class="text-muted mb-0">Belum ada peserta berdaftar.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Bil</th>
                                    <th>Nama</th>
                                    <th>Rumah Sukan</th>
                                    <th>Jantina</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($peserta as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_peserta }}</td>
                                        <td>{{ $item->rumahSukan->nama_rumah ?? '-' }}</td>
                                        <td>{{ $item->jantina === 'L' ? 'Lelaki' : ($item->jantina === 'P' ? 'Perempuan' : '-') }}</td>
                                        <td>
                                            <form action="{{ route('peserta.destroy', $item->id) }}" method="POST"
                                                  onsubmit="return confirm(@js('Padam peserta ' . $item->nama_peserta . '? Keputusan peserta ini juga akan dipadamkan.'));">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Padam</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
            </div>
        </main>
    </div>
</body>
</html>

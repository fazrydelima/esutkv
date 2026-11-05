<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru {{ $user->rumahSukan->nama_rumah ?? '' }} - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Panel Guru ' . ($user->rumahSukan->nama_rumah ?? ''),
                'subtitle' => 'Hantar keputusan peserta rumah sukan anda'
            ])
            <div class="page-content">
                <div class="page-intro">
                    <h2>Hantar keputusan</h2>
                    <p>Keputusan akan menunggu pengesahan admin sebelum dipaparkan pada live scoreboard.</p>
                </div>

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

                <section class="card mb-4">
                    <div class="card-header">Keputusan untuk {{ $user->rumahSukan->nama_rumah ?? 'rumah sukan anda' }}</div>
                    <div class="card-body">
                        @if ($acara->isEmpty() || $peserta->isEmpty())
                            <p class="mb-0">Acara atau peserta rumah sukan anda belum didaftarkan. Hubungi admin.</p>
                        @else
                            <form action="{{ route('guru.keputusan.store') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="acara_id" class="form-label">Acara</label>
                                        <select name="acara_id" id="acara_id" class="form-select" required>
                                            <option value="">-- Pilih acara --</option>
                                            @foreach ($acara as $item)
                                                <option value="{{ $item->id }}" {{ old('acara_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_acara }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="peserta_id" class="form-label">Peserta</label>
                                        <select name="peserta_id" id="peserta_id" class="form-select" required>
                                            <option value="">-- Pilih peserta rumah anda --</option>
                                            @foreach ($peserta as $item)
                                                <option value="{{ $item->id }}" {{ old('peserta_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_peserta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pingat" class="form-label">Keputusan</label>
                                        <select name="pingat" id="pingat" class="form-select" required>
                                            <option value="">-- Pilih pingat --</option>
                                            @foreach (['Emas' => 10, 'Perak' => 8, 'Gangsa' => 6] as $pingat => $mata)
                                                <option value="{{ $pingat }}" {{ old('pingat') === $pingat ? 'selected' : '' }}>
                                                    {{ $pingat }} ({{ $mata }} mata)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Hantar untuk Pengesahan</button>
                            </form>
                        @endif
                    </div>
                </section>

                <section class="card">
                    <div class="card-header">Keputusan yang dihantar</div>
                    <div class="card-body">
                        @if ($keputusans->isEmpty())
                            <p class="mb-0">Belum ada keputusan dihantar.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Acara</th>
                                            <th>Peserta</th>
                                            <th>Pingat</th>
                                            <th>Mata</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($keputusans as $item)
                                            <tr>
                                                <td>{{ $item->acara->nama_acara }}</td>
                                                <td>{{ $item->peserta->nama_peserta }}</td>
                                                <td>{{ $item->pingat }}</td>
                                                <td>{{ $item->mata }}</td>
                                                <td>
                                                    {{ ['pending' => 'Menunggu pengesahan', 'approved' => 'Disahkan', 'rejected' => 'Ditolak'][$item->status] }}
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

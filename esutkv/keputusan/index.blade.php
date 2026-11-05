<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Keputusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Pengurusan Keputusan',
                'subtitle' => 'Rekod pingat dan mata acara'
            ])
            <div class="page-content">
        <div class="page-intro">
            <h2>Daftar keputusan acara</h2>
            <p>Pilih acara, peserta dan pingat yang dimenangi.</p>
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
            <div class="card-header">Daftar Keputusan</div>
            <div class="card-body">
                <form action="{{ route('keputusan.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="acara_id" class="form-label">Acara</label>
                            <select name="acara_id" id="acara_id"
                                    class="form-select @error('acara_id') is-invalid @enderror" required>
                                <option value="">-- Pilih acara --</option>
                                @foreach ($acara as $item)
                                    <option value="{{ $item->id }}" {{ old('acara_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_acara }}
                                    </option>
                                @endforeach
                            </select>
                            @error('acara_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="peserta_id" class="form-label">Peserta</label>
                            <select name="peserta_id" id="peserta_id"
                                    class="form-select @error('peserta_id') is-invalid @enderror" required>
                                <option value="">-- Pilih peserta --</option>
                                @foreach ($peserta as $item)
                                    <option value="{{ $item->id }}" {{ old('peserta_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_peserta }} — {{ $item->rumahSukan->nama_rumah ?? 'Tiada rumah sukan' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peserta_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="pingat" class="form-label">Pingat</label>
                            <select name="pingat" id="pingat"
                                    class="form-select @error('pingat') is-invalid @enderror" required>
                                <option value="">-- Pilih pingat --</option>
                                <option value="Emas" {{ old('pingat') === 'Emas' ? 'selected' : '' }}>Emas (10 mata)</option>
                                <option value="Perak" {{ old('pingat') === 'Perak' ? 'selected' : '' }}>Perak (8 mata)</option>
                                <option value="Gangsa" {{ old('pingat') === 'Gangsa' ? 'selected' : '' }}>Gangsa (6 mata)</option>
                            </select>
                            @error('pingat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Keputusan</button>
                </form>
            </div>
        </section>

        <section class="card">
            <div class="card-header">Keputusan terkini</div>
            <div class="card-body">
                @if ($keputusans->isEmpty())
                    <p class="text-muted mb-0">Belum ada keputusan direkodkan.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Bil</th>
                                    <th>Acara</th>
                                    <th>Peserta</th>
                                    <th>Rumah Sukan</th>
                                    <th>Pingat</th>
                                    <th>Mata</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keputusans as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->acara->nama_acara ?? '-' }}</td>
                                        <td>{{ $item->peserta->nama_peserta ?? '-' }}</td>
                                        <td>{{ $item->peserta->rumahSukan->nama_rumah ?? '-' }}</td>
                                        <td><span class="pingat-badge">{{ $item->pingat }}</span></td>
                                        <td>{{ $item->mata }}</td>
                                        <td>{{ $item->status === 'approved' ? 'Disahkan' : ($item->status === 'pending' ? 'Menunggu pengesahan' : 'Ditolak') }}</td>
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

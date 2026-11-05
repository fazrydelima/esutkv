<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Pengurusan Peserta',
                'subtitle' => 'Pendaftaran dan maklumat atlet'
            ])
            <div class="page-content">
        <div class="page-intro">
            <h2>Daftar dan urus peserta</h2>
            <p>Rekodkan maklumat peserta dan rumah sukan mereka.</p>
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

        <section class="card mb-4" id="form-tambah-peserta">
            <div class="card-header">Daftar Peserta</div>
            <div class="card-body">
                <form action="{{ route('peserta.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="nama_peserta" class="form-label">Nama peserta</label>
                            <input type="text" name="nama_peserta" id="nama_peserta"
                                   class="form-control js-uppercase @error('nama_peserta') is-invalid @enderror"
                                   value="{{ old('nama_peserta') }}" maxlength="255"
                                   oninput="this.value = this.value.toLocaleUpperCase()" required>
                            @error('nama_peserta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="rumah_sukan_id" class="form-label">Rumah sukan</label>
                            <select name="rumah_sukan_id" id="rumah_sukan_id"
                                    class="form-select @error('rumah_sukan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih rumah sukan --</option>
                                @foreach ($rumahSukans as $rumah)
                                    <option value="{{ $rumah->id }}" {{ old('rumah_sukan_id') == $rumah->id ? 'selected' : '' }}>
                                        {{ $rumah->nama_rumah }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rumah_sukan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="jantina" class="form-label">Jantina</label>
                            <select name="jantina" id="jantina"
                                    class="form-select @error('jantina') is-invalid @enderror" required>
                                <option value="">-- Pilih jantina --</option>
                                <option value="L" {{ old('jantina') === 'L' ? 'selected' : '' }}>Lelaki</option>
                                <option value="P" {{ old('jantina') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jantina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Daftar Peserta</button>
                </form>
            </div>
        </section>

        <section class="card" id="senarai-peserta">
            <div class="card-header">Senarai Peserta</div>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($peserta as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_peserta }}</td>
                                        <td>{{ $item->rumahSukan->nama_rumah ?? '-' }}</td>
                                        <td>{{ $item->jantina === 'L' ? 'Lelaki' : ($item->jantina === 'P' ? 'Perempuan' : '-') }}</td>
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

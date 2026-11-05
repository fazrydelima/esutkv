<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mata Sukantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Tambah Mata Sukantara',
                'subtitle' => 'Pengisian keputusan'
            ])
            <div class="page-content">
            <div class="page-intro">
                <h2>Masukkan mata bagi setiap rumah</h2>
                <p>Pilih acara Sukantara dan masukkan mata terkumpul.</p>
            </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Mata Sukantara</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('sukantara.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="acara_id" class="form-label">Pilih Acara</label>
                                <select name="acara_id" id="acara_id" class="form-control @error('acara_id') is-invalid @enderror" required>
                                    <option value="">-- Sila Pilih Acara --</option>
                                    @foreach($acara as $item)
                                        <option value="{{ $item->acara_id }}" {{ old('acara_id') == $item->acara_id ? 'selected' : '' }}>
                                            {{ $item->nama_acara }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('acara_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h5 class="mb-3">Masukkan Mata untuk Setiap Rumah Sukan</h5>

                            @foreach($rumahSukan as $rumah)
                                <div class="mb-3">
                                    <label for="mata_{{ $rumah->rumah_id }}" class="form-label">
                                        {{ $rumah->nama_rumah }}
                                    </label>
                                    <input type="number"
                                           name="mata[{{ $rumah->rumah_id }}]"
                                           id="mata_{{ $rumah->rumah_id }}"
                                           class="form-control @error('mata.' . $rumah->rumah_id) is-invalid @enderror"
                                           value="{{ old('mata.' . $rumah->rumah_id, 0) }}"
                                           min="0"
                                           required>
                                    @error('mata.' . $rumah->rumah_id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('sukantara.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </main>
    </div>
</body>
</html>
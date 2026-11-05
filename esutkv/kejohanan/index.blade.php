<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Kejohanan - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Kejohanan',
                'subtitle' => 'Pengisian keputusan dan rekod'
            ])
            <div class="page-content">
    <div class="container mt-4">
        <h2>Pengurusan Keputusan & Kejohanan</h2>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="row mt-4">
            <!-- Borang Input Keputusan -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Masukkan Keputusan (1, 2, 3)</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kejohanan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="acara_id" class="form-label">Pilih Acara</label>
                                <select name="acara_id" id="acara_id" class="form-select" required>
                                    <option value="">-- Pilih Acara --</option>
                                    @isset($acara)
                                        @foreach($acara as $item)
                                            <option value="{{ $item->id }}" {{ old('acara_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_acara }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-warning fw-bold">Tempat Pertama (Emas)</label>
                                <select name="peserta_id[1]" class="form-select" required>
                                    <option value="">-- Pilih Peserta --</option>
                                    @isset($peserta)
                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_peserta }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-bold">Tempat Kedua (Perak)</label>
                                <select name="peserta_id[2]" class="form-select" required>
                                    <option value="">-- Pilih Peserta --</option>
                                    @isset($peserta)
                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_peserta }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-danger fw-bold">Tempat Ketiga (Gangsa)</label>
                                <select name="peserta_id[3]" class="form-select" required>
                                    <option value="">-- Pilih Peserta --</option>
                                    @isset($peserta)
                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_peserta }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Simpan Keputusan</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Jadual Senarai Keputusan -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Senarai Keputusan Direkodkan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Acara</th>
                                    <th>Peserta</th>
                                    <th>Kedudukan</th>
                                    <th>Mata</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($kejohanan)
                                    @forelse($kejohanan as $index => $k)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $k->acara->nama_acara ?? '-' }}</td>
                                            <td>{{ $k->peserta->nama_peserta ?? '-' }}</td>
                                            <td>{{ $k->pingat }}</td>
                                            <td>{{ $k->mata }}</td>
                                            <td>
                                                <a href="{{ route('kejohanan.edit', $k->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('kejohanan.delete', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Adakah anda pasti?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Padam</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tiada rekod keputusan lagi.</td>
                                        </tr>
                                    @endforelse
                                @endisset
                            </tbody>
                        </table>
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
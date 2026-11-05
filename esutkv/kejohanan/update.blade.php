<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Keputusan - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Kemaskini Keputusan',
                'subtitle' => 'Pengurusan kejohanan'
            ])
    <div class="page-content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">Kemaskini Keputusan Kejohanan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kejohanan.update', $kejohanan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="acara_id" class="form-label">Acara</label>
                                <select name="acara_id" id="acara_id" class="form-select" required>
                                    <option value="">-- Pilih Acara --</option>
                                    @foreach($acara as $item)
                                        <option value="{{ $item->id }}" {{ $kejohanan->acara_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_acara }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="peserta_id" class="form-label">Peserta</label>
                                <select name="peserta_id" id="peserta_id" class="form-select" required>
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach($peserta as $p)
                                        <option value="{{ $p->id }}" {{ $kejohanan->peserta_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_peserta }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kedudukan" class="form-label">Kedudukan</label>
                                <select name="kedudukan" id="kedudukan" class="form-select" required>
                                    <option value="1" {{ $kejohanan->pingat === 'Emas' ? 'selected' : '' }}>1 (Emas)</option>
                                    <option value="2" {{ $kejohanan->pingat === 'Perak' ? 'selected' : '' }}>2 (Perak)</option>
                                    <option value="3" {{ $kejohanan->pingat === 'Gangsa' ? 'selected' : '' }}>3 (Gangsa)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="mata" class="form-label">Jumlah Mata</label>
                                <input type="number" name="mata" id="mata" class="form-control" value="{{ $kejohanan->mata }}" min="0" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kejohanan.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Kemaskini Rekod</button>
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
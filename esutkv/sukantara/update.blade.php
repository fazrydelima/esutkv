<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Mata Sukantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="app-shell">
    @include('esutkv.partials.sidebar')
    <main class="app-main">
        @include('esutkv.partials.topbar', [
            'title' => 'Kemaskini Sukantara',
            'subtitle' => 'Pengurusan keputusan'
        ])
        <div class="page-content">
        <div class="page-intro">
            <h2>Kemaskini mata acara</h2>
            <p>{{ $acara->nama_acara }}</p>
        </div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kemaskini Mata Sukantara: {{ $acara->nama_acara }}</div>

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

                    <form action="{{ route('sukantara.update', $acara->acara_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label"><strong>Acara:</strong></label>
                            <input type="text" class="form-control" value="{{ $acara->nama_acara }}" disabled>
                            <input type="hidden" name="acara_id" value="{{ $acara->acara_id }}">
                        </div>

                        <hr>
                        <h5 class="mb-3">Kemaskini Mata untuk Setiap Rumah Sukan</h5>

                        @foreach($rumahSukan as $rumah)
                            @php
                                $currentMata = isset($keputusan[$rumah->rumah_id]) ? $keputusan[$rumah->rumah_id]->mata : 0;
                            @endphp
                            <div class="mb-3">
                                <label for="mata_{{ $rumah->rumah_id }}" class="form-label">
                                    {{ $rumah->nama_rumah }}
                                </label>
                                <input type="number" 
                                       name="mata[{{ $rumah->rumah_id }}]" 
                                       id="mata_{{ $rumah->rumah_id }}" 
                                       class="form-control @error('mata.' . $rumah->rumah_id) is-invalid @enderror" 
                                       value="{{ old('mata.' . $rumah->rumah_id, $currentMata) }}" 
                                       min="0" 
                                       required>
                                @error('mata.' . $rumah->rumah_id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sukantara.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Kemaskini</button>
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
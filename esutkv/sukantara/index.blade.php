<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Keputusan Sukantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="app-shell">
    @include('esutkv.partials.sidebar')
    <main class="app-main">
        @include('esutkv.partials.topbar', [
            'title' => 'Sukantara',
            'subtitle' => 'Keputusan mata mengikut rumah sukan'
        ])
        <div class="page-content">
        <div class="page-intro">
            <h2>Senarai keputusan Sukantara</h2>
            <p>Mata terkumpul bagi setiap acara dan rumah sukan.</p>
        </div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Senarai Keputusan Sukantara</h5>
                    <a href="{{ route('sukantara.create') }}" class="btn btn-primary btn-sm">Tambah Mata Sukantara</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @php
                        $groupedKeputusan = $keputusan->groupBy('acara_id');
                    @endphp

                    @if($groupedKeputusan->isEmpty())
                        <p class="text-center text-muted">Tiada rekod keputusan Sukantara ditemui.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Bil</th>
                                        <th style="width: 30%;">Nama Acara</th>
                                        <th style="width: 45%;">Mata Rumah Sukan</th>
                                        <th style="width: 20%;">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedKeputusan as $acaraId => $items)
                                        @php $acara = $items->first()->acara; @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $acara->nama_acara ?? 'Acara Tidak Dikenali' }}</td>
                                            <td>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($items as $k)
                                                        <li>
                                                            <strong>{{ $k->rumah_sukan->nama_rumah ?? 'Rumah Sukan' }}:</strong> 
                                                            {{ $k->mata }} mata
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <a href="{{ route('sukantara.edit', $acaraId) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('sukantara.destroy', $acaraId) }}" method="POST" class="d-inline" onsubmit="return confirm('Adakah anda pasti mahu memadam keputusan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Padam</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
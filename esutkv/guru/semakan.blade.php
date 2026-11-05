<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semakan Keputusan Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Semakan Keputusan Guru',
                'subtitle' => 'Sahkan sebelum keputusan masuk scoreboard'
            ])
            <div class="page-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif

                <section class="card">
                    <div class="card-header">Keputusan menunggu pengesahan</div>
                    <div class="card-body">
                        @if ($keputusans->isEmpty())
                            <p class="mb-0">Tiada keputusan menunggu pengesahan.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Acara</th>
                                            <th>Peserta</th>
                                            <th>Rumah</th>
                                            <th>Pingat</th>
                                            <th>Mata</th>
                                            <th>Dihantar oleh</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($keputusans as $item)
                                            <tr>
                                                <td>{{ $item->acara->nama_acara }}</td>
                                                <td>{{ $item->peserta->nama_peserta }}</td>
                                                <td>{{ $item->rumahSukan->nama_rumah }}</td>
                                                <td>{{ $item->pingat }}</td>
                                                <td>{{ $item->mata }}</td>
                                                <td>{{ $item->penghantar->email }}</td>
                                                <td class="d-flex gap-2">
                                                    <form action="{{ route('admin.guru-keputusan.update', $item) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-sm btn-success">Sahkan</button>
                                                    </form>
                                                    <form action="{{ route('admin.guru-keputusan.update', $item) }}" method="POST"
                                                          onsubmit="return confirm('Tolak keputusan ini?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
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

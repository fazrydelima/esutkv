<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')

        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Dashboard',
                'subtitle' => 'Ringkasan pertandingan'
            ])

            <div class="page-content">
                <div class="page-intro">
                    <h2>Selamat datang, {{ auth()->user()->name ?? 'Admin' }}</h2>
                    <p>Pantau penyertaan, kedudukan rumah sukan dan keputusan terkini.</p>
                </div>

                <section class="stats-grid" aria-label="Ringkasan sistem">
                    <article class="stat-card stat-blue">
                        <div class="stat-label">Jumlah Peserta</div>
                        <div class="stat-value">{{ $totalPeserta }}</div>
                    </article>
                    <article class="stat-card stat-green">
                        <div class="stat-label">Jumlah Acara</div>
                        <div class="stat-value">{{ $totalAcara }}</div>
                    </article>
                    <article class="stat-card stat-gold">
                        <div class="stat-label">Rumah Sukan</div>
                        <div class="stat-value">{{ $totalRumahSukan }}</div>
                    </article>
                </section>

                <div class="dashboard-grid">
                    <section class="surface-card">
                        <div class="surface-card-header">
                            <h2>Kedudukan Rumah Sukan</h2>
                            <span class="topbar-eyebrow">Jumlah mata</span>
                        </div>
                        <div class="surface-card-body">
                            <div class="house-score-list">
                                @forelse ($rumahSukans as $rumah)
                                    <article class="house-score-card" style="--house-color: {{ $rumah->warna ?? '#6955e8' }}">
                                        <span class="house-score-name">{{ $rumah->nama_rumah }}</span>
                                        <span class="house-score-value">{{ $rumah->jumlah_mata ?? 0 }} <small>mata</small></span>
                                    </article>
                                @empty
                                    <p class="muted-text">Belum ada rumah sukan berdaftar.</p>
                                @endforelse
                            </div>
                        </div>
                    </section>

                    <section class="surface-card">
                        <div class="surface-card-header">
                            <h2>Keputusan Terkini</h2>
                            <span class="topbar-eyebrow">10 rekod terakhir</span>
                        </div>
                        <div class="surface-card-body">
                            @if ($keputusans->isEmpty())
                                <p class="muted-text">Belum ada keputusan direkodkan.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Acara</th>
                                                <th>Peserta</th>
                                                <th>Pingat</th>
                                                <th>Mata</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($keputusans as $keputusan)
                                                <tr>
                                                    <td>{{ $keputusan->acara->nama_acara ?? '-' }}</td>
                                                    <td>{{ $keputusan->peserta->nama_peserta ?? '-' }}</td>
                                                    <td>{{ $keputusan->pingat ?? $keputusan->jenis_keputusan ?? '-' }}</td>
                                                    <td>{{ $keputusan->mata }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

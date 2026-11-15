
@extends('layouts.app_guru')

@section('content')
    @include('esutkv.partials.topbar', [
        'title' => 'Dashboard Guru',
        'subtitle' => 'Ringkasan prestasi rumah sukan'
    ])

    <div class="page-content">

        {{-- Pengenalan --}}
        <div class="page-intro">
            <h2>
                Selamat datang, {{ $user->name ?? 'Guru' }}
            </h2>
            <p>
                Pantau jumlah peserta, kutipan mata dan keputusan
                terkini rumah sukan {{ $user->rumahSukan->nama_rumah ?? '' }}.
            </p>
        </div>

        {{-- Kad Statistik --}}
        <section class="stats-grid" aria-label="Ringkasan rumah sukan">

            <article class="stat-card stat-blue">
                <div class="stat-label">Jumlah Peserta</div>
                <div class="stat-value">{{ $jumlahPeserta }}</div>
                <small>Ahli rumah sukan</small>
            </article>

            <article class="stat-card stat-green">
                <div class="stat-label">Jumlah Mata</div>
                <div class="stat-value">{{ $jumlahMata }}</div>
                <small>Jumlah keseluruhan mata</small>
            </article>

            <article class="stat-card stat-gold">
                <div class="stat-label">Rumah Sukan</div>
                <div class="stat-value" style="font-size: 24px;">
                    {{ $user->rumahSukan->nama_rumah ?? '-' }}
                </div>
                <small>Rumah sukan anda</small>
            </article>

        </section>

        {{-- Maklumat Rumah Sukan --}}
        <section class="surface-card">
            <div class="surface-card-header">
                <h2>Maklumat Rumah Sukan</h2>
                <span class="topbar-eyebrow">Prestasi semasa</span>
            </div>

            <div class="surface-card-body">
                <div class="house-score-list">

                    <article class="house-score-card"
                        style="--house-color: {{ $user->rumahSukan->warna ?? '#6955e8' }}">

                        <span class="house-score-name">
                            {{ $user->rumahSukan->nama_rumah ?? 'Tiada rumah sukan' }}
                        </span>

                        <span class="house-score-value">
                            {{ $jumlahMata }}
                            <small>mata</small>
                        </span>

                    </article>

                </div>
            </div>
        </section>

        {{-- Keputusan Terkini --}}
        <section class="surface-card" style="margin-top: 24px;">

            <div class="surface-card-header">
                <h2>Keputusan Terkini</h2>
                <span class="topbar-eyebrow">10 rekod terakhir</span>
            </div>

            <div class="surface-card-body">

                @if ($keputusans->isEmpty())

                    <p class="muted-text">
                        Belum ada keputusan direkodkan untuk rumah sukan anda.
                    </p>

                @else

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Modul</th>
                                    <th>Acara</th>
                                    <th>Peserta</th>
                                    <th>Keputusan</th>
                                    <th>Mata</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($keputusans as $index => $keputusan)

                                    @php
                                        if ($keputusan->jenis_keputusan === 'terawal') {
                                            $modul = 'Merentas Desa';
                                            $acara = 'Merentas Desa';
                                            $peserta = $keputusan->peserta->nama_peserta ?? '-';
                                            $keputusanLabel = 'Tempat ' . ($keputusan->kedudukan ?? '-');

                                        } elseif ($keputusan->jenis_keputusan === 'masa_terhad') {
                                            $modul = 'Merentas Desa';
                                            $acara = 'Merentas Desa';
                                            $peserta = 'Kiraan Peserta';
                                            $keputusanLabel = 'Dalam had masa';

                                        } elseif (
                                            $keputusan->peserta_id === null &&
                                            $keputusan->pingat === null &&
                                            $keputusan->jenis_keputusan === null
                                        ) {
                                            $modul = 'Sukantara';
                                            $acara = $keputusan->acara->nama_acara ?? '-';
                                            $peserta = 'Semua ahli rumah sukan';
                                            $keputusanLabel = '-';

                                        } else {
                                            $modul = 'Kejohanan';
                                            $acara = $keputusan->acara->nama_acara ?? '-';
                                            $peserta = $keputusan->peserta->nama_peserta ?? '-';
                                            $keputusanLabel = $keputusan->pingat ?? '-';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $modul }}
                                            </span>
                                        </td>

                                        <td>{{ $acara }}</td>

                                        <td>{{ $peserta }}</td>

                                        <td>{{ $keputusanLabel }}</td>

                                        <td>
                                            <strong>{{ $keputusan->mata }}</strong>
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
@endsection

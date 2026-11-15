
@extends('layouts.app_guru')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Pengurusan Peserta',
        'subtitle' => 'Pangkalan data ahli rumah sukan'
    ])

    <div class="page-content">

        {{-- Pengenalan halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-intro mb-0">
                <h2>Senarai Peserta</h2>
                <p>
                    Urus maklumat ahli rumah sukan anda.
                </p>
            </div>

            <a href="#form-tambah-peserta" class="btn btn-primary">
                + Daftar Peserta
            </a>
        </div>

        {{-- Mesej maklum balas --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Paparan ralat validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Sila semak maklumat berikut:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Statistik peserta --}}
        <section class="stats-grid mb-4">

            <article class="stat-card stat-blue">
                <div class="stat-label">Jumlah Peserta</div>
                <div class="stat-value">{{ $peserta->count() }}</div>
            </article>

            <article class="stat-card stat-green">
                <div class="stat-label">Peserta Lelaki</div>
                <div class="stat-value">
                    {{ $peserta->where('jantina', 'L')->count() }}
                </div>
            </article>

            <article class="stat-card stat-gold">
                <div class="stat-label">Peserta Perempuan</div>
                <div class="stat-value">
                    {{ $peserta->where('jantina', 'P')->count() }}
                </div>
            </article>

        </section>

        {{-- Borang tambah peserta --}}
        <section class="surface-card mb-4" id="form-tambah-peserta">

            <div class="surface-card-header">
                <h2>Daftar Peserta Baharu</h2>
                <span class="topbar-eyebrow">Maklumat ahli</span>
            </div>

            <div class="surface-card-body">

                <form action="{{ route('peserta.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Peserta</label>
                            <input
                                type="text"
                                name="nama_peserta"
                                class="form-control"
                                value="{{ old('nama_peserta') }}"
                                placeholder="Masukkan nama peserta"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jantina</label>
                            <select name="jantina" class="form-select" required>
                                <option value="">-- Pilih Jantina --</option>
                                <option value="L" {{ old('jantina') == 'L' ? 'selected' : '' }}>
                                    Lelaki
                                </option>
                                <option value="P" {{ old('jantina') == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Peserta
                    </button>

                </form>

            </div>
        </section>

        {{-- Jadual senarai peserta --}}
        <section class="surface-card">

            <div class="surface-card-header">
                <h2>Ahli Rumah Sukan</h2>
                <span class="topbar-eyebrow">
                    {{ $peserta->count() }} rekod
                </span>
            </div>

            <div class="surface-card-body">

                @if ($peserta->isEmpty())

                    <p class="muted-text mb-0">
                        Belum ada peserta berdaftar.
                    </p>

                @else

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Nama Peserta</th>
                                    <th>Rumah Sukan</th>
                                    <th>Jantina</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($peserta as $item)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $item->nama_peserta }}</strong>
                                        </td>

                                        <td>
                                            {{ $item->rumahSukan->nama_rumah ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $item->jantina === 'L'
                                                ? 'Lelaki'
                                                : ($item->jantina === 'P' ? 'Perempuan' : '-') }}
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">

                                                {{-- Kemaskini --}}
                                                <a href="{{ route('peserta.edit', $item->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    Kemaskini
                                                </a>

                                                {{-- Padam --}}
                                                <form
                                                    action="{{ route('peserta.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm(@js('Adakah anda pasti mahu memadam peserta ' . $item->nama_peserta . '?'));">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                        Padam
                                                    </button>

                                                </form>

                                            </div>
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

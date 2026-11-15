
@extends('layouts.app_admin')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Pengurusan Akaun Guru',
        'subtitle' => 'Pendaftaran dan pengurusan akaun guru'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Pengurusan Akaun Guru</h2>
            <p>
                Daftar akaun guru dan tetapkan akses mengikut rumah sukan masing-masing.
            </p>
        </div>

        {{-- Mesej berjaya --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mesej ralat --}}
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


        {{-- STATISTIK AKAUN --}}
        <section class="stats-grid mb-4">

            <article class="stat-card stat-blue">
                <div class="stat-label">Jumlah Akaun Guru</div>
                <div class="stat-value">{{ $akaunGuru->count() }}</div>
            </article>

            <article class="stat-card stat-green">
                <div class="stat-label">Rumah Sukan Berdaftar</div>
                <div class="stat-value">
                    {{ $akaunGuru->pluck('rumah_sukan_id')->unique()->count() }}
                </div>
            </article>

            <article class="stat-card stat-gold">
                <div class="stat-label">Rumah Sukan</div>
                <div class="stat-value">{{ $rumahSukans->count() }}</div>
            </article>

        </section>


        {{-- TAMBAH AKAUN GURU --}}
        <section class="surface-card mb-4">

            <div class="surface-card-header">
                <h2>Daftar Akaun Guru Baharu</h2>
                <span class="topbar-eyebrow">Akaun pengguna</span>
            </div>

            <div class="surface-card-body">

                <form action="{{ route('guru-accounts.store') }}"
                      method="POST">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama Guru</label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama guru"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alamat Emel</label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="contoh@email.com"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rumah Sukan</label>

                            <select name="rumah_sukan_id"
                                    class="form-select"
                                    required>

                                <option value="">-- Pilih Rumah Sukan --</option>

                                @foreach ($rumahSukans as $rumah)
                                    <option value="{{ $rumah->id }}"
                                        {{ old('rumah_sukan_id') == $rumah->id ? 'selected' : '' }}>
                                        {{ $rumah->nama_rumah }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kata Laluan</label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   minlength="12"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sahkan Kata Laluan</label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   minlength="12"
                                   autocomplete="new-password"
                                   required>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        + Daftar Akaun Guru
                    </button>

                </form>

            </div>
        </section>


        {{-- SENARAI AKAUN GURU --}}
        <section class="surface-card">

            <div class="surface-card-header">
                <h2>Senarai Akaun Guru</h2>
                <span class="topbar-eyebrow">
                    {{ $akaunGuru->count() }} akaun
                </span>
            </div>

            <div class="surface-card-body">

                @forelse ($akaunGuru as $guru)

                    <div class="card mb-3">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <strong>
                                {{ $guru->rumahSukan->nama_rumah ?? 'Rumah belum ditetapkan' }}
                            </strong>

                            <span class="badge bg-success">
                                Guru
                            </span>

                        </div>

                        <div class="card-body">

                            <p>
                                <strong>Nama:</strong>
                                {{ $guru->name }}
                            </p>

                            <p>
                                <strong>Emel:</strong>
                                {{ $guru->email }}
                            </p>

                            <hr>

                            <h6 class="mb-3">Tetapkan Semula Kata Laluan</h6>

                            <form action="{{ route('guru-accounts.password', $guru) }}"
                                  method="POST">

                                @csrf
                                @method('PUT')

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Kata Laluan Baharu
                                        </label>

                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               minlength="12"
                                               autocomplete="new-password"
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Sahkan Kata Laluan
                                        </label>

                                        <input type="password"
                                               name="password_confirmation"
                                               class="form-control"
                                               minlength="12"
                                               autocomplete="new-password"
                                               required>
                                    </div>

                                </div>

                                <button type="submit"
                                        class="btn btn-warning mt-3">
                                    Tetapkan Kata Laluan
                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    <p class="muted-text mb-0">
                        Belum ada akaun guru didaftarkan.
                    </p>

                @endforelse

            </div>
        </section>

    </div>

@endsection

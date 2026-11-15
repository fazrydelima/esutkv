
@extends('layouts.app_guru')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Kemaskini Peserta',
        'subtitle' => 'Ubah maklumat ahli rumah sukan'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Kemaskini Maklumat Peserta</h2>
            <p>Ubah maklumat peserta yang telah didaftarkan.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="surface-card">

            <div class="surface-card-header">
                <h2>Maklumat Peserta</h2>
            </div>

            <div class="surface-card-body">

                <form action="{{ route('peserta.update', $peserta->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Peserta</label>
                        <input
                            type="text"
                            name="nama_peserta"
                            class="form-control"
                            value="{{ old('nama_peserta', $peserta->nama_peserta) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jantina</label>
                        <select name="jantina" class="form-select" required>
                            <option value="L"
                                {{ old('jantina', $peserta->jantina) == 'L' ? 'selected' : '' }}>
                                Lelaki
                            </option>

                            <option value="P"
                                {{ old('jantina', $peserta->jantina) == 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('peserta.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>
        </section>

    </div>

@endsection

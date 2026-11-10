@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Merentas Desa',
        'subtitle' => 'Kemaskini Keputusan Merentas Desa'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Kemaskini Keputusan Merentas Desa</h2>
            <p>Kemaskini maklumat atlet dan kedudukan dalam acara Merentas Desa.</p>
        </div>


        {{-- Paparan ralat --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Sila semak maklumat berikut:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <section class="card mb-4">

            <div class="card-header">
                <strong>Maklumat Atlet</strong>
            </div>

            <div class="card-body">

                <form action="{{ route('merentas_desa.update', $keputusan->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')


                    {{-- Nama atlet --}}
                    <div class="mb-3">

                        <label for="nama_peserta" class="form-label">
                            Nama Atlet
                        </label>

                        <input type="text"
                               name="nama_peserta"
                               id="nama_peserta"
                               class="form-control @error('nama_peserta') is-invalid @enderror"
                               value="{{ old('nama_peserta', $keputusan->peserta->nama_peserta ?? '') }}"
                               maxlength="255"
                               required>

                        @error('nama_peserta')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Rumah sukan --}}
                    <div class="mb-3">

                        <label for="rumah_sukan_id" class="form-label">
                            Rumah Sukan
                        </label>

                        <select name="rumah_sukan_id"
                                id="rumah_sukan_id"
                                class="form-select @error('rumah_sukan_id') is-invalid @enderror"
                                required>

                            <option value="">-- Pilih Rumah Sukan --</option>

                            @foreach($rumahSukan as $rumah)

                                <option value="{{ $rumah->id }}"
                                    {{ old('rumah_sukan_id', $keputusan->rumah_sukan_id) == $rumah->id ? 'selected' : '' }}>

                                    {{ $rumah->nama_rumah }}

                                </option>

                            @endforeach

                        </select>

                        @error('rumah_sukan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Kedudukan --}}
                    <div class="mb-3">

                        <label for="kedudukan" class="form-label">
                            Kedudukan
                        </label>

                        <select name="kedudukan"
                                id="kedudukan"
                                class="form-select @error('kedudukan') is-invalid @enderror"
                                required>

                            <option value="">-- Pilih Kedudukan --</option>

                            @for($i = 1; $i <= 20; $i++)

                                <option value="{{ $i }}"
                                    {{ old('kedudukan', $keputusan->kedudukan) == $i ? 'selected' : '' }}>

                                    Tempat {{ $i }}

                                </option>

                            @endfor

                        </select>

                        @error('kedudukan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Mata automatik --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Mata
                        </label>

                        <input type="text"
                               id="mata"
                               class="form-control"
                               value="{{ $keputusan->mata }}"
                               disabled>

                        <small class="text-muted">
                            Mata dikira secara automatik berdasarkan kedudukan atlet.
                        </small>

                    </div>


                    {{-- Butang --}}
                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('merentas_desa.index') }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-save"></i>
                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </div>


    {{-- Kiraan mata automatik --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const kedudukan = document.getElementById('kedudukan');
            const mata = document.getElementById('mata');

            function kiraMata() {

                const nilai = parseInt(kedudukan.value);

                if (nilai >= 1 && nilai <= 20) {
                    mata.value = 21 - nilai;
                } else {
                    mata.value = '';
                }

            }

            kedudukan.addEventListener('change', kiraMata);

            kiraMata();

        });
    </script>

@endsection

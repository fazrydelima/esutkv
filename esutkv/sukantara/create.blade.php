@extends('layouts.app')

@section('content')
    @include('esutkv.partials.topbar', [
        'title' => 'Tambah Mata Sukantara',
        'subtitle' => 'Pengisian keputusan'
    ])

    <div class="page-content">
        <div class="page-intro">
            <h2>Masukkan Mata bagi Setiap Rumah</h2>
            <p>Pilih acara dan masukkan mata terkumpul bagi setiap rumah sukan.</p>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Tambah Mata Sukantara
                        </div>

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

                            <form action="{{ route('sukantara.store') }}" method="POST">
                                @csrf

                                {{-- Pilih Acara --}}
                                <div class="mb-3">
                                    <label for="acara_id" class="form-label">
                                        Pilih Acara
                                    </label>

                                    <select name="acara_id"
                                            id="acara_id"
                                            class="form-control @error('acara_id') is-invalid @enderror"
                                            required>

                                        <option value="">-- Sila Pilih Acara --</option>

                                        @foreach($acara as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('acara_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_acara }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('acara_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <hr>

                                <h5 class="mb-3">
                                    Masukkan Mata untuk Setiap Rumah Sukan
                                </h5>

                                {{-- Input Mata Rumah Sukan --}}
                                @foreach($rumahSukan as $rumah)
                                    <div class="mb-3">
                                        <label for="mata_{{ $rumah->id }}" class="form-label">
                                            {{ $rumah->nama_rumah }}
                                        </label>

                                        <input type="number"
                                               name="mata[{{ $rumah->id }}]"
                                               id="mata_{{ $rumah->id }}"
                                               class="form-control @error('mata.' . $rumah->id) is-invalid @enderror"
                                               value="{{ old('mata.' . $rumah->id, 0) }}"
                                               min="0"
                                               required>

                                        @error('mata.' . $rumah->id)
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endforeach

                                {{-- Butang --}}
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('sukantara.index') }}"
                                       class="btn btn-secondary">
                                        Kembali
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        Simpan
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

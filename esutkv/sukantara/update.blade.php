@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Kemaskini Sukantara',
        'subtitle' => 'Pengurusan keputusan'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Kemaskini Mata Acara</h2>
            <p>{{ $acara->nama_acara }}</p>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card">

                        <div class="card-header">
                            Kemaskini Mata Sukantara: {{ $acara->nama_acara }}
                        </div>

                        <div class="card-body">

                            {{-- Paparan ralat --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('sukantara.update', $acara->id) }}"
                                  method="POST">

                                @csrf
                                @method('PUT')

                                {{-- Maklumat Acara --}}
                                <div class="mb-3">

                                    <label class="form-label">
                                        <strong>Acara:</strong>
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           value="{{ $acara->nama_acara }}"
                                           disabled>

                                    <input type="hidden"
                                           name="acara_id"
                                           value="{{ $acara->id }}">

                                </div>

                                <hr>

                                <h5 class="mb-3">
                                    Kemaskini Mata untuk Setiap Rumah Sukan
                                </h5>

                                {{-- Input Mata Rumah Sukan --}}
                                @foreach($rumahSukan as $rumah)

                                    @php
                                        $currentMata = isset($keputusan[$rumah->id])
                                            ? $keputusan[$rumah->id]->mata
                                            : 0;
                                    @endphp

                                    <div class="mb-3">

                                        <label for="mata_{{ $rumah->id }}"
                                               class="form-label">

                                            {{ $rumah->nama_rumah }}

                                        </label>

                                        <input type="number"
                                               name="mata[{{ $rumah->id }}]"
                                               id="mata_{{ $rumah->id }}"
                                               class="form-control @error('mata.' . $rumah->id) is-invalid @enderror"
                                               value="{{ old('mata.' . $rumah->id, $currentMata) }}"
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

                                    <button type="submit" class="btn btn-primary">
                                        Kemaskini
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


@extends('layouts.app')

@section('content')
    @include('esutkv.partials.topbar', [
        'title' => 'Kemaskini Keputusan',
        'subtitle' => 'Kemaskini rekod kejohanan'
    ])

    <div class="page-content">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Kemaskini Keputusan</h5>
                </div>

                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kejohanan.update', $kejohanan->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Pilih Acara</label>

                            <select name="acara_id" class="form-select" required>
                                @foreach($acara as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('acara_id', $kejohanan->acara_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_acara }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Peserta</label>

                            <select name="peserta_id" class="form-select" required>
                                @foreach($peserta as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('peserta_id', $kejohanan->peserta_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_peserta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kedudukan</label>

                            <select name="kedudukan" class="form-select" required>
                                <option value="1"
                                    {{ old('kedudukan', $kejohanan->pingat == 'Emas' ? 1 : '') == 1 ? 'selected' : '' }}>
                                    Johan (Emas) - 10 Mata
                                </option>

                                <option value="2"
                                    {{ old('kedudukan', $kejohanan->pingat == 'Perak' ? 2 : '') == 2 ? 'selected' : '' }}>
                                    Naib Johan (Perak) - 8 Mata
                                </option>

                                <option value="3"
                                    {{ old('kedudukan', $kejohanan->pingat == 'Gangsa' ? 3 : '') == 3 ? 'selected' : '' }}>
                                    Tempat Ketiga (Gangsa) - 6 Mata
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mata</label>
                            <input type="text"
                                class="form-control"
                                value="{{ $kejohanan->mata }}"
                                readonly>
                            <small class="text-muted">
                                Mata akan dikemaskini secara automatik mengikut kedudukan.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('kejohanan.index') }}"
                            class="btn btn-secondary">
                            Kembali
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

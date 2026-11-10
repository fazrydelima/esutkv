
@extends('layouts.app')

@section('content')
    @include('esutkv.partials.topbar', [
        'title' => 'Kejohanan',
        'subtitle' => 'Pengisian keputusan dan rekod'
    ])

    <div class="page-content">
        <div class="container mt-4">
            <h2>Pengurusan Keputusan & Kejohanan</h2>

            {{-- Mesej berjaya --}}
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mesej ralat validation --}}
            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <strong>Sila semak maklumat berikut:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mt-4">

                {{-- BORANG INPUT KEPUTUSAN --}}
                <div class="col-md-5 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Masukkan Keputusan</h5>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('kejohanan.store') }}" method="POST">
                                @csrf

                                {{-- Pilih acara --}}
                                <div class="mb-3">
                                    <label for="acara_id" class="form-label">
                                        Pilih Acara
                                    </label>

                                    <select name="acara_id" id="acara_id"
                                        class="form-select" required>
                                        <option value="">-- Pilih Acara --</option>

                                        @foreach($acara as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('acara_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_acara }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tempat pertama --}}
                                <div class="mb-3">
                                    <label class="form-label text-warning fw-bold">
                                        Tempat Pertama (Emas) - 10 Mata
                                    </label>

                                    <select name="peserta_id[1]" class="form-select" required>
                                        <option value="">-- Pilih Peserta --</option>

                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('peserta_id.1') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_peserta }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tempat kedua --}}
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-bold">
                                        Tempat Kedua (Perak) - 8 Mata
                                    </label>

                                    <select name="peserta_id[2]" class="form-select" required>
                                        <option value="">-- Pilih Peserta --</option>

                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('peserta_id.2') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_peserta }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tempat ketiga --}}
                                <div class="mb-3">
                                    <label class="form-label text-danger fw-bold">
                                        Tempat Ketiga (Gangsa) - 6 Mata
                                    </label>

                                    <select name="peserta_id[3]" class="form-select" required>
                                        <option value="">-- Pilih Peserta --</option>

                                        @foreach($peserta as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('peserta_id.3') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_peserta }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Simpan Keputusan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- SENARAI KEPUTUSAN --}}
                <div class="col-md-7 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Senarai Keputusan Direkodkan</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Acara</th>
                                            <th>Peserta</th>
                                            <th>Pingat</th>
                                            <th>Mata</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($kejohanan as $index => $k)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $k->acara->nama_acara ?? '-' }}</td>
                                                <td>{{ $k->peserta->nama_peserta ?? '-' }}</td>
                                                <td>{{ $k->pingat }}</td>
                                                <td>{{ $k->mata }}</td>
                                                <td>
                                                    <a href="{{ route('kejohanan.edit', $k->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>

                                                    <form
                                                        action="{{ route('kejohanan.delete', $k->id) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Adakah anda pasti mahu memadam rekod ini?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                            Padam
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Tiada rekod keputusan lagi.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

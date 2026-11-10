```blade
@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Merentas Desa',
        'subtitle' => 'Pengurusan Merentas Desa'
    ])

    <div class="page-content">

        <div class="page-intro d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2>Merentas Desa</h2>
                <p>Pengurusan keputusan dan rekod Merentas Desa.</p>
            </div>

            <a href="{{ route('merentas_desa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Keputusan
            </a>
        </div>


        {{-- Mesej berjaya --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- Mesej ralat --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- Ralat validation --}}
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


        @php
            $atletTerawal = $keputusan
                ->where('jenis_keputusan', 'terawal')
                ->sortBy('kedudukan');

            $rekodMasaTerhad = $keputusan
                ->where('jenis_keputusan', 'masa_terhad');

            $jumlahMata = $keputusan->sum('mata');
        @endphp


        <section class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Senarai Keputusan Merentas Desa</span>

                <span class="badge bg-primary">
                    {{ $keputusan->count() }} Rekod
                </span>
            </div>


            <div class="card-body">

                @if($keputusan->isNotEmpty())

                    <div class="border rounded mb-4">

                        {{-- Header --}}
                        <div class="p-3 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div>
                                <h5 class="mb-1">
                                    Keputusan Merentas Desa
                                </h5>

                                <small class="text-muted">
                                    Jumlah rekod: {{ $keputusan->count() }}
                                </small>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">

                                <span class="badge bg-success">
                                    {{ $jumlahMata }} Mata
                                </span>

                                <a href="{{ route('merentas_desa.show') }}"
                                   class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                    Lihat Keputusan
                                </a>

                            </div>

                        </div>


                        {{-- Jadual 20 atlet terawal --}}
                        <div class="p-3">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-trophy"></i>
                                20 Atlet Terawal
                            </h6>

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover align-middle">

                                    <thead class="table-light">
                                        <tr>
                                            <th width="10%">Kedudukan</th>
                                            <th>Nama Atlet</th>
                                            <th>Rumah Sukan</th>
                                            <th width="15%">Mata</th>
                                            <th width="15%">Tindakan</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($atletTerawal as $atlet)

                                            <tr>

                                                <td class="text-center">
                                                    {{ $atlet->kedudukan }}
                                                </td>

                                                <td>
                                                    {{ $atlet->peserta->nama_peserta ?? 'Tiada nama' }}
                                                </td>

                                                <td>
                                                    {{ $atlet->rumahSukan->nama_rumah ?? 'Tiada rumah sukan' }}
                                                </td>

                                                <td class="text-center">
                                                    <strong>{{ $atlet->mata }}</strong>
                                                </td>

                                                <td class="text-center">

                                                    <a href="{{ route('merentas_desa.edit', $atlet->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Kemaskini">
                                                        <i class="bi bi-pencil-square">
                                                            Edit
                                                        </i>
                                                    </a>

                                                    <form action="{{ route('merentas_desa.destroy', $atlet->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Adakah anda pasti mahu memadam rekod atlet ini?')">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                title="Padam">
                                                            <i class="bi bi-trash">
                                                                Padam
                                                            </i>
                                                        </button>

                                                    </form>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    Tiada rekod atlet terawal.
                                                </td>
                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        {{-- Jadual counter masa terhad --}}
                        <div class="px-3 pb-3">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-stopwatch"></i>
                                Atlet Dalam Masa Terhad
                            </h6>

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover align-middle">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Rumah Sukan</th>
                                            <th width="25%">Jumlah Atlet</th>
                                            <th width="25%">Jumlah Mata</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($rekodMasaTerhad as $counter)

                                            <tr>

                                                <td>
                                                    {{ $counter->rumahSukan->nama_rumah ?? 'Tiada rumah sukan' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $counter->mata }}
                                                </td>

                                                <td class="text-center">
                                                    <strong>{{ $counter->mata }}</strong>
                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    Tiada rekod atlet masa terhad.
                                                </td>
                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                            <small class="text-muted">
                                * Setiap atlet yang berjaya menamatkan larian dalam masa ditetapkan diberikan 1 mata.
                            </small>

                        </div>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-clipboard-data display-4 text-muted"></i>

                        <h5 class="mt-3">Tiada Keputusan Merentas Desa</h5>

                        <p class="text-muted">
                            Belum ada keputusan Merentas Desa yang dimasukkan.
                        </p>

                        <a href="{{ route('merentas_desa.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Keputusan
                        </a>

                    </div>

                @endif

            </div>

        </section>

    </div>

@endsection
```

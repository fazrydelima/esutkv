
@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Merentas Desa',
        'subtitle' => 'Paparan Keputusan Merentas Desa'
    ])

    <div class="page-content">

        {{-- Tajuk halaman --}}
        <div class="page-intro d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h2>Keputusan Merentas Desa</h2>
                <p>Keputusan penuh dan jumlah mata mengikut rumah sukan.</p>
            </div>

            <a href="{{ route('merentas_desa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

        </div>


        {{-- Maklumat acara --}}
        <section class="card mb-4">

            <div class="card-header">
                <strong>Maklumat Acara</strong>
            </div>

            <div class="card-body">

                <h4 class="mb-2">
                    MERENTAS DESA
                </h4>

                <p class="text-muted mb-0">
                    Jumlah rekod keputusan: {{ $keputusan->count() }}
                </p>

            </div>

        </section>


        {{-- JUMLAH MATA RUMAH SUKAN --}}
        <section class="card mb-4">

            <div class="card-header">
                <strong>1. Kedudukan Rumah Sukan</strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th width="10%">Kedudukan</th>
                                <th>Rumah Sukan</th>
                                <th width="20%">Mata 20 Atlet Terawal</th>
                                <th width="20%">Mata Masa Terhad</th>
                                <th width="20%">Jumlah Mata</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($keputusanRumah as $index => $rumah)

                                <tr>

                                    <td class="text-center">

                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark">
                                                1
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">
                                                2
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger">
                                                3
                                            </span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif

                                    </td>

                                    <td class="fw-bold">
                                        {{ $rumah['nama_rumah'] }}
                                    </td>

                                    <td class="text-center">
                                        {{ $rumah['mata_20_terawal'] }}
                                    </td>

                                    <td class="text-center">
                                        {{ $rumah['mata_masa_terhad'] }}
                                    </td>

                                    <td class="text-center fw-bold">
                                        {{ $rumah['jumlah_mata'] }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tiada rekod keputusan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        {{-- 20 ATLET TERAWAL --}}
        <section class="card mb-4">

            <div class="card-header">
                <strong>2. Senarai 20 Atlet Terawal</strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th width="10%">Kedudukan</th>
                                <th>Nama Atlet</th>
                                <th>Rumah Sukan</th>
                                <th width="15%">Mata</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                $atletTerawal = $keputusan
                                    ->where('jenis_keputusan', 'terawal')
                                    ->sortBy('kedudukan');
                            @endphp

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

                                    <td class="text-center fw-bold">
                                        {{ $atlet->mata }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tiada rekod atlet terawal.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        {{-- ATLET MASA TERHAD --}}
        <section class="card mb-4">

            <div class="card-header">
                <strong>3. Atlet Yang Menamatkan Larian Dalam Masa Terhad</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-info">
                    Setiap atlet yang berjaya menamatkan larian dalam tempoh masa ditetapkan diberikan 1 mata.
                </div>


                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Rumah Sukan</th>
                                <th width="25%">Bilangan Atlet</th>
                                <th width="25%">Jumlah Mata</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                $counter = $keputusan->where(
                                    'jenis_keputusan',
                                    'masa_terhad'
                                );
                            @endphp

                            @forelse($counter as $rekod)

                                <tr>

                                    <td class="fw-bold">
                                        {{ $rekod->rumahSukan->nama_rumah ?? 'Tiada rumah sukan' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $rekod->mata }}
                                    </td>

                                    <td class="text-center fw-bold">
                                        {{ $rekod->mata }}
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

            </div>

        </section>


        {{-- BUTANG KEMBALI --}}
        <div class="d-flex justify-content-end mb-4">

            <a href="{{ route('merentas_desa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Senarai
            </a>

        </div>

    </div>

@endsection


@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Sukantara',
        'subtitle' => 'Keputusan mata mengikut rumah sukan'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Senarai Keputusan Sukantara</h2>
            <p>Mata terkumpul bagi setiap acara dan rumah sukan.</p>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">

                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Senarai Keputusan Sukantara</h5>

                            <a href="{{ route('sukantara.create') }}"
                               class="btn btn-primary btn-sm">
                                Tambah Mata Sukantara
                            </a>
                        </div>

                        <div class="card-body">

                            {{-- Mesej berjaya --}}
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Mesej ralat --}}
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Ralat validation --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @php
                                $groupedKeputusan = $keputusan->groupBy('acara_id');
                            @endphp

                            @if($groupedKeputusan->isEmpty())

                                <p class="text-center text-muted">
                                    Tiada rekod keputusan Sukantara ditemui.
                                </p>

                            @else

                                <div class="table-responsive">

                                    <table class="table table-bordered table-striped">

                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">Bil</th>
                                                <th style="width: 30%;">Nama Acara</th>
                                                <th style="width: 45%;">Mata Rumah Sukan</th>
                                                <th style="width: 20%;">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach($groupedKeputusan as $acaraId => $items)

                                                @php
                                                    $acara = $items->first()->acara;
                                                @endphp

                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>

                                                    <td>
                                                        {{ $acara->nama_acara ?? 'Acara Tidak Dikenali' }}
                                                    </td>

                                                    <td>
                                                        <ul class="mb-0 ps-3">

                                                            @foreach($items as $k)

                                                                <li>
                                                                    <strong>
                                                                        {{ $k->rumahSukan->nama_rumah ?? 'Rumah Sukan Tidak Dikenali' }}:
                                                                    </strong>

                                                                    {{ $k->mata }} mata
                                                                </li>

                                                            @endforeach

                                                        </ul>
                                                    </td>

                                                    <td>

                                                        <a href="{{ route('sukantara.edit', $acaraId) }}"
                                                           class="btn btn-warning btn-sm">
                                                            Edit
                                                        </a>

                                                        <form action="{{ route('sukantara.destroy', $acaraId) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Adakah anda pasti mahu memadam keputusan ini?');">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="btn btn-danger btn-sm">
                                                                Padam
                                                            </button>

                                                        </form>

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

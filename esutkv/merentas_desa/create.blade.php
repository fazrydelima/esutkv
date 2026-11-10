```blade
@extends('layouts.app')

@section('content')

    @include('esutkv.partials.topbar', [
        'title' => 'Merentas Desa',
        'subtitle' => 'Pengisian Keputusan Merentas Desa'
    ])

    <div class="page-content">

        <div class="page-intro">
            <h2>Pengisian Keputusan Merentas Desa</h2>
            <p>Masukkan maklumat 20 atlet terawal dan bilangan atlet yang menamatkan larian dalam masa ditetapkan.</p>
        </div>


        {{-- Paparan ralat validation --}}
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


        <form action="{{ route('merentas_desa.store') }}" method="POST">

            @csrf

            {{-- 20 ATLET TERAWAL --}}
            <section class="card mb-4">

                <div class="card-header">
                    <strong>1. Senarai 20 Atlet Terawal</strong>
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>

                        Masukkan nama atlet mengikut urutan mereka sampai ke garisan penamat.

                        <ul class="mb-0 mt-2">
                            <li>Tempat pertama: 20 mata</li>
                            <li>Tempat kedua: 19 mata</li>
                            <li>Tempat ketiga: 18 mata</li>
                            <li>Sehingga tempat ke-20: 1 mata</li>
                        </ul>
                    </div>


                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Kedudukan</th>
                                    <th>Nama Atlet</th>
                                    <th width="30%">Rumah Sukan</th>
                                    <th width="15%">Mata</th>
                                </tr>
                            </thead>

                            <tbody>

                                @for($i = 0; $i < 20; $i++)

                                    <tr>

                                        <td class="text-center fw-bold">
                                            {{ $i + 1 }}
                                        </td>


                                        <td>

                                            <input type="text"
                                                   name="atlet[{{ $i }}][nama]"
                                                   class="form-control"
                                                   placeholder="Nama atlet"
                                                   value="{{ old('atlet.'.$i.'.nama') }}"
                                                   maxlength="255"
                                                   required>

                                        </td>


                                        <td>

                                            <select name="atlet[{{ $i }}][rumah_sukan_id]"
                                                    class="form-select"
                                                    required>

                                                <option value="">
                                                    -- Pilih Rumah --
                                                </option>

                                                @foreach($rumahSukan as $rumah)

                                                    <option value="{{ $rumah->id }}"
                                                        {{ old('atlet.'.$i.'.rumah_sukan_id') == $rumah->id ? 'selected' : '' }}>

                                                        {{ $rumah->nama_rumah }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        <td class="text-center fw-bold">
                                            {{ 20 - $i }}
                                        </td>

                                    </tr>

                                @endfor

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>



            {{-- ATLET MASA TERHAD --}}
            <section class="card mb-4">

                <div class="card-header">
                    <strong>2. Atlet Dalam Masa Terhad</strong>
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>

                        Masukkan bilangan atlet daripada setiap rumah sukan yang berjaya menamatkan larian dalam tempoh masa ditetapkan.

                        <ul class="mb-0 mt-2">
                            <li>Setiap atlet mendapat 1 mata.</li>
                            <li>Jumlah keseluruhan atlet tidak boleh melebihi 20 orang.</li>
                            <li>Counter hanya menyimpan bilangan atlet mengikut rumah sukan.</li>
                        </ul>
                    </div>


                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Rumah Sukan</th>
                                    <th width="30%">Bilangan Atlet</th>
                                    <th width="20%">Jumlah Mata</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($rumahSukan as $rumah)

                                    <tr>

                                        <td class="fw-bold">
                                            {{ $rumah->nama_rumah }}
                                        </td>


                                        <td>

                                            <input type="number"
                                                   name="counter[{{ $rumah->id }}]"
                                                   class="form-control counter-input"
                                                   min="0"
                                                   max="20"
                                                   value="{{ old('counter.'.$rumah->id, 0) }}"
                                                   required>

                                        </td>


                                        <td class="text-center">

                                            <span class="mata-counter fw-bold">
                                                {{ old('counter.'.$rumah->id, 0) }}
                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                            <tfoot class="table-light">

                                <tr>
                                    <th>Jumlah Keseluruhan</th>

                                    <th>
                                        <span id="jumlah-atlet">0</span> / 20 orang
                                    </th>

                                    <th>
                                        <span id="jumlah-mata">0</span> mata
                                    </th>
                                </tr>

                            </tfoot>

                        </table>

                    </div>


                    <div id="counter-warning" class="alert alert-danger d-none">
                        Jumlah atlet melebihi 20 orang. Sila ubah bilangan atlet.
                    </div>

                </div>

            </section>



            {{-- BUTANG --}}
            <div class="d-flex justify-content-end gap-2 mb-4">

                <a href="{{ route('merentas_desa.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>


                <button type="submit"
                        id="btn-simpan"
                        class="btn btn-primary">

                    <i class="bi bi-save"></i>
                    Simpan Keputusan

                </button>

            </div>


        </form>

    </div>


    {{-- JAVASCRIPT KIRAAN COUNTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const counterInputs = document.querySelectorAll('.counter-input');

            const jumlahAtlet = document.getElementById('jumlah-atlet');
            const jumlahMata = document.getElementById('jumlah-mata');

            const warning = document.getElementById('counter-warning');
            const btnSimpan = document.getElementById('btn-simpan');

            function kiraCounter() {

                let jumlah = 0;

                counterInputs.forEach(function (input) {

                    let nilai = parseInt(input.value) || 0;

                    jumlah += nilai;

                    // 1 atlet = 1 mata
                    const mata = input.closest('tr').querySelector('.mata-counter');

                    mata.textContent = nilai;

                });

                jumlahAtlet.textContent = jumlah;
                jumlahMata.textContent = jumlah;

                if (jumlah > 20) {

                    warning.classList.remove('d-none');
                    btnSimpan.disabled = true;

                } else {

                    warning.classList.add('d-none');
                    btnSimpan.disabled = false;

                }

            }


            counterInputs.forEach(function (input) {

                input.addEventListener('input', kiraCounter);

            });


            // Kiraan awal apabila halaman dibuka
            kiraCounter();

        });
    </script>

@endsection
```
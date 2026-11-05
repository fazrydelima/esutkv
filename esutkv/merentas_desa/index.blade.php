<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merentas Desa - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('esutkv.partials.sidebar')
        <main class="app-main">
            @include('esutkv.partials.topbar', [
                'title' => 'Merentas Desa',
                'subtitle' => 'Pengurusan Merentas Desa'
            ])
            <div class="page-content">
                <div class="page-intro">
                    <h2>Merentas Desa</h2>
                    <p>Halaman khusus untuk pengurusan Merentas Desa.</p>
                </div>

                <section class="card">
                    <div class="card-header">Pengurusan Merentas Desa</div>
                    <div class="card-body">
                        <p class="mb-0">Bahagian ini dikhaskan untuk Merentas Desa dan tidak memaparkan senarai acara kejohanan.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Scoreboard - eSUTKV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { min-height: 100vh; background: #f3f5fa; }
        .scoreboard-shell { max-width: 1180px; margin: 0 auto; padding: 32px 20px; }
        .scoreboard-header, .scoreboard-panel { background: #fff; border: 1px solid #e3e8f1; border-radius: 16px; }
        .scoreboard-header { padding: 24px; margin-bottom: 20px; }
        .scoreboard-logos { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .scoreboard-logos img { width: 52px; height: 52px; object-fit: contain; border-radius: 8px; }
        .scoreboard-panel { padding: 22px; height: 100%; }
        .scoreboard-house { border-left: 5px solid var(--house-color); background: #f8f9fc; border-radius: 10px; padding: 16px; display: flex; justify-content: space-between; gap: 12px; }
        .scoreboard-house strong { color: var(--house-color); }
    </style>
</head>
<body>
    <main class="scoreboard-shell">
        <header class="scoreboard-header">
            <div class="scoreboard-logos">
                <img src="{{ asset('image/logo esutkv.jpg') }}" alt="Logo eSUTKV">
                <img src="{{ asset('image/logo kv.jpg') }}" alt="Logo Kolej Vokasional">
                <span class="badge text-bg-success">LIVE</span>
            </div>
            <h1 class="h3 mb-1">Kedudukan Kejohanan Sukan</h1>
            <p class="text-secondary mb-0">Mata rasmi dikemas kini selepas admin mengesahkan keputusan.</p>
        </header>

        <div id="scoreboard-error" class="alert alert-warning d-none" role="alert"></div>
        <p class="text-secondary small" aria-live="polite">Kemas kini terakhir: <time id="scoreboard-updated">{{ $dikemasKini }}</time> · auto-refresh setiap 10 saat</p>

        <div class="row g-4">
            <div class="col-lg-5">
                <section class="scoreboard-panel">
                    <h2 class="h5 mb-3">Kedudukan Rumah Sukan</h2>
                    <div class="d-grid gap-2" id="scoreboard-houses">
                        @forelse ($rumahSukans as $rumah)
                            <article class="scoreboard-house" style="--house-color: {{ $rumah['warna'] }}">
                                <strong>{{ $rumah['nama'] }}</strong>
                                <span><b>{{ $rumah['mata'] }}</b> mata</span>
                            </article>
                        @empty
                            <p class="mb-0">Belum ada rumah sukan.</p>
                        @endforelse
                    </div>
                </section>
            </div>
            <div class="col-lg-7">
                <section class="scoreboard-panel">
                    <h2 class="h5 mb-3">Keputusan Terkini</h2>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Acara</th>
                                    <th>Peserta</th>
                                    <th>Rumah</th>
                                    <th>Pingat</th>
                                    <th>Mata</th>
                                </tr>
                            </thead>
                            <tbody id="scoreboard-results">
                                @forelse ($keputusans as $keputusan)
                                    <tr>
                                        <td>{{ $keputusan['acara'] }}</td>
                                        <td>{{ $keputusan['peserta'] }}</td>
                                        <td>{{ $keputusan['rumah'] }}</td>
                                        <td>{{ $keputusan['pingat'] }}</td>
                                        <td>{{ $keputusan['mata'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Belum ada keputusan disahkan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <script>
        const housesElement = document.getElementById('scoreboard-houses');
        const resultsElement = document.getElementById('scoreboard-results');
        const updatedElement = document.getElementById('scoreboard-updated');
        const errorElement = document.getElementById('scoreboard-error');

        function appendCell(row, value) {
            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);
        }

        function renderScoreboard(data) {
            housesElement.replaceChildren();
            if (data.rumahSukans.length === 0) {
                housesElement.textContent = 'Belum ada rumah sukan.';
            } else {
                data.rumahSukans.forEach((house) => {
                    const card = document.createElement('article');
                    card.className = 'scoreboard-house';
                    card.style.setProperty('--house-color', house.warna);
                    const name = document.createElement('strong');
                    name.textContent = house.nama;
                    const points = document.createElement('span');
                    const total = document.createElement('b');
                    total.textContent = house.mata;
                    points.append(total, document.createTextNode(' mata'));
                    card.append(name, points);
                    housesElement.appendChild(card);
                });
            }

            resultsElement.replaceChildren();
            if (data.keputusans.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 5;
                cell.className = 'text-center';
                cell.textContent = 'Belum ada keputusan disahkan.';
                row.appendChild(cell);
                resultsElement.appendChild(row);
            } else {
                data.keputusans.forEach((result) => {
                    const row = document.createElement('tr');
                    [result.acara, result.peserta, result.rumah, result.pingat, result.mata].forEach((value) => appendCell(row, value));
                    resultsElement.appendChild(row);
                });
            }

            updatedElement.textContent = data.dikemasKini;
            errorElement.classList.add('d-none');
        }

        async function refreshScoreboard() {
            try {
                const response = await fetch(@json(route('scoreboard.data')), {
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store'
                });
                if (!response.ok) {
                    throw new Error(`Scoreboard returned HTTP ${response.status}`);
                }
                renderScoreboard(await response.json());
            } catch (error) {
                errorElement.textContent = 'Scoreboard gagal dikemas kini. Cuba muat semula halaman.';
                errorElement.classList.remove('d-none');
                console.error(error);
            }
        }

        window.setInterval(refreshScoreboard, 10000);
    </script>
</body>
</html>

<aside class="app-sidebar">
    <a class="sidebar-brand" href="{{ auth()->user()->role === 'guru' ? route('guru.dashboard') : route('dashboard') }}" aria-label="eSUTKV Dashboard">
        <span class="sidebar-brand-logos">
            <img src="{{ asset('image/logo esutkv.jpg') }}" alt="Logo eSUTKV">
            <img src="{{ asset('image/logo kv.jpg') }}" alt="Logo Kolej Vokasional">
        </span>
        <span class="sidebar-brand-title">Sistem Sukan</span>
    </a>

    <nav class="sidebar-nav" aria-label="Navigasi utama">
        @if (auth()->user()->role === 'guru')
            <span class="sidebar-label">RUMAH SUKAN {{ auth()->user()->rumahSukan->nama_rumah ?? '' }}</span>
            <a class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" href="{{ route('guru.dashboard') }}">
                <span class="sidebar-icon">▤</span> Hantar Keputusan
            </a>
            <a class="sidebar-link" href="{{ route('scoreboard') }}" target="_blank" rel="noopener">
                <span class="sidebar-icon">◉</span> Live Scoreboard
            </a>
        @else
        <span class="sidebar-label">MENU UTAMA</span>
        <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <span class="sidebar-icon">⌂</span> Dashboard
        </a>

        <span class="sidebar-label">PESERTA</span>
        <a class="sidebar-link {{ request()->routeIs('peserta.senarai') ? 'active' : '' }}" href="{{ route('peserta.senarai') }}">
            <span class="sidebar-icon">♙</span> Senarai Peserta
        </a>
        <a class="sidebar-link {{ request()->routeIs('peserta.index') ? 'active' : '' }}" href="{{ route('peserta.index') }}#form-tambah-peserta">
            <span class="sidebar-icon">＋</span> Daftar Peserta
        </a>

        <span class="sidebar-label">MERENTAS DESA</span>
        <a class="sidebar-link {{ request()->routeIs('merentasdesa.*') ? 'active' : '' }}" href="{{ route('merentasdesa.index') }}">
            <span class="sidebar-icon">◷</span> Merentas Desa
        </a>
        <a class="sidebar-link {{ request()->routeIs('sukantara.*') ? 'active' : '' }}" href="{{ route('sukantara.index') }}">
            <span class="sidebar-icon">◈</span> Sukantara
        </a>
        <a class="sidebar-link {{ request()->routeIs('kejohanan.*') ? 'active' : '' }}" href="{{ route('kejohanan.index') }}">
            <span class="sidebar-icon">⚑</span> Kejohanan
        </a>

        <span class="sidebar-label">KEPUTUSAN</span>
        <a class="sidebar-link {{ request()->routeIs('keputusan.*') ? 'active' : '' }}" href="{{ route('keputusan.index') }}">
            <span class="sidebar-icon">▤</span> Senarai & Pengisian
        </a>
        <a class="sidebar-link {{ request()->routeIs('admin.guru-keputusan.*') ? 'active' : '' }}" href="{{ route('admin.guru-keputusan.index') }}">
            <span class="sidebar-icon">✓</span> Semakan Keputusan Guru
        </a>
        <a class="sidebar-link {{ request()->routeIs('admin.guru-accounts.*') ? 'active' : '' }}" href="{{ route('admin.guru-accounts.index') }}">
            <span class="sidebar-icon">♙</span> Akaun Guru
        </a>
        <a class="sidebar-link" href="{{ route('scoreboard') }}" target="_blank" rel="noopener">
            <span class="sidebar-icon">◉</span> Live Scoreboard
        </a>
        @endif
    </nav>

    <form action="{{ route('esutkv.logout') }}" method="POST" class="sidebar-logout">
        @csrf
        <button type="submit" class="sidebar-link">
            <span class="sidebar-icon">↪</span> Log Keluar
        </button>
    </form>
</aside>

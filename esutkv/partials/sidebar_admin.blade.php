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

        <span class="sidebar-label">KATEGORI ACARA</span>
        <a class="sidebar-link {{ request()->routeIs('merentas_desa.*') ? 'active' : '' }}" href="{{ route('merentas_desa.index') }}">
            <span class="sidebar-icon">◷</span> Merentas Desa
        </a>
        <a class="sidebar-link {{ request()->routeIs('sukantara.*') ? 'active' : '' }}" href="{{ route('sukantara.index') }}">
            <span class="sidebar-icon">◈</span> Sukantara
        </a>
        <a class="sidebar-link {{ request()->routeIs('kejohanan.*') ? 'active' : '' }}" href="{{ route('kejohanan.index') }}">
            <span class="sidebar-icon">⚑</span> Kejohanan
        </a>

        <span class="sidebar-label">PENGURUSAN</span>
        <a class="sidebar-link {{ request()->routeIs('guru-accounts.*') ? 'active' : '' }}" href="{{ route('guru-accounts.index') }}">
            <span class="sidebar-icon">♙</span> Akaun Guru
        </a>
        
        <span class="sidebar-label">PAPARAN MATA</span>
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

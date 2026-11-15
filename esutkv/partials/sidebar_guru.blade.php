
<aside class="app-sidebar">

    {{-- LOGO DAN BRAND --}}
    <a class="sidebar-brand"
       href="{{ auth()->user()->role === 'guru'
            ? route('guru.dashboard')
            : route('dashboard') }}"
       aria-label="eSUTKV Dashboard">

        <span class="sidebar-brand-logos">
            <img src="{{ asset('image/logo esutkv.jpg') }}"
                 alt="Logo eSUTKV">

            <img src="{{ asset('image/logo kv.jpg') }}"
                 alt="Logo Kolej Vokasional">
        </span>

        <span class="sidebar-brand-title">Sistem Sukan</span>
    </a>


    <nav class="sidebar-nav" aria-label="Navigasi utama">

        @if (auth()->user()->role === 'guru')

            {{-- MENU UTAMA --}}
            <span class="sidebar-label">MENU UTAMA</span>

            <a class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
               href="{{ route('guru.dashboard') }}">
                <span class="sidebar-icon">⌂</span>
                Dashboard
            </a>


            {{-- PENGURUSAN PESERTA --}}
            <span class="sidebar-label">PENGURUSAN PESERTA</span>

            <a class="sidebar-link {{ request()->routeIs('peserta.index', 'peserta.senarai') ? 'active' : '' }}"
               href="{{ route('peserta.index') }}">
                <span class="sidebar-icon">♙</span>
                Senarai Peserta
            </a>


            {{-- PAPARAN MATA --}}
            <span class="sidebar-label">PAPARAN MATA</span>

            <a class="sidebar-link"
               href="{{ route('scoreboard') }}"
               target="_blank"
               rel="noopener">
                <span class="sidebar-icon">◉</span>
                Live Scoreboard
            </a>


        @else

            {{-- MENU ADMIN --}}
            <span class="sidebar-label">MENU UTAMA</span>

            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <span class="sidebar-icon">⌂</span>
                Dashboard
            </a>

            {{-- Kekalkan pautan modul admin sedia ada di sini --}}

        @endif

    </nav>


    {{-- LOG KELUAR --}}
    <form action="{{ route('esutkv.logout') }}"
          method="POST"
          class="sidebar-logout">

        @csrf

        <button type="submit" class="sidebar-link">
            <span class="sidebar-icon">↪</span>
            Log Keluar
        </button>

    </form>

</aside>

<header class="app-topbar">
    <div>
        <p class="topbar-eyebrow">{{ $subtitle ?? 'Sistem Pengurusan Sukan' }}</p>
        <h1>{{ $title }}</h1>
    </div>
    <div class="user-chip">
        <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
        <span>
            <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
            <small>Pengurusan Kejohanan</small>
        </span>
    </div>
</header>

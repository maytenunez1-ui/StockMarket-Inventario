<header class="site-header">
    <div class="topbar topbar-wide">
        <a href="{{ route('home') }}" class="brand-lockup">
            <span class="brand-mark">BD</span>
            <span>
                <span class="brand-title">Biblioteca Digital</span>
                <span class="brand-subtitle">Catalogo, prestamos y gestion</span>
            </span>
        </a>

        <nav class="site-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Inicio</a>
            <a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.*') ? 'is-active' : '' }}">Catalogo</a>
            @auth
                <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'is-active' : '' }}">Mis prestamos</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Panel admin</a>
                @endif
            @endauth
        </nav>

        <div class="auth-actions">
            @guest
                <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesion</a>
            @else
                <div class="user-pill">
                    <span>{{ auth()->user()->name }}</span>
                    <small>{{ auth()->user()->role }}</small>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Cerrar sesion</button>
                </form>
            @endguest
        </div>
    </div>
</header>

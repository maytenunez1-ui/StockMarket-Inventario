<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel administrador')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/library.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="site-body">
    <div class="ambient-shape shape-left"></div>
    <div class="ambient-shape shape-right"></div>

    @include('partials.site-header')

    <main class="page-shell admin-shell">
        <aside class="admin-sidebar card-surface">
            <p class="sidebar-eyebrow">Administracion</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Resumen</a>
            <a href="{{ route('admin.books.index') }}" class="sidebar-link {{ request()->routeIs('admin.books.*') ? 'is-active' : '' }}">Productos</a>
            <a href="{{ route('admin.authors.index') }}" class="sidebar-link {{ request()->routeIs('admin.authors.*') ? 'is-active' : '' }}">Marcas</a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">Categorias</a>
            <a href="{{ route('admin.publishers.index') }}" class="sidebar-link {{ request()->routeIs('admin.publishers.*') ? 'is-active' : '' }}">Proveedores</a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">Usuarios</a>
            <a href="{{ route('admin.loans.index') }}" class="sidebar-link {{ request()->routeIs('admin.loans.*') ? 'is-active' : '' }}">Compras</a>
        </aside>

        <section class="admin-content">
            @include('partials.flash')
            @yield('content')
        </section>
    </main>
</body>
</html>

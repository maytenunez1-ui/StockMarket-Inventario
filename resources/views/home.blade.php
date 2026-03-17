@extends('layouts.app')

@section('title', 'Biblioteca Digital')

@section('content')
    <section class="hero hero-panel">
        <div>
            <p class="eyebrow">Biblioteca web funcional</p>
            <h1>Explora libros, gestiona prestamos y administra el catalogo desde un solo lugar.</h1>
            <p class="hero-text">
                Los visitantes pueden descubrir el catalogo. Los usuarios registrados pueden solicitar prestamos.
                El administrador controla libros, autores, categorias, editoriales, usuarios y estados de prestamo.
            </p>
            <div class="hero-actions">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Ver catalogo</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Crear cuenta</a>
                @else
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Mis prestamos</a>
                @endguest
            </div>
        </div>

        <div class="stats-grid">
            <article class="stat-card">
                <span class="stat-value">{{ $stats['books'] }}</span>
                <span class="stat-label">Libros activos</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['users'] }}</span>
                <span class="stat-label">Usuarios registrados</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['loans'] }}</span>
                <span class="stat-label">Prestamos activos</span>
            </article>
        </div>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Categorias principales</p>
                <h2>Rutas de lectura para empezar</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-link">Ver todo el catalogo</a>
        </div>

        <div class="grid-panel compact-grid">
            @forelse ($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="card card-link">
                    <p class="card-label">Categoria</p>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->books_count }} libros relacionados.</p>
                </a>
            @empty
                <article class="card">
                    <h3>Sin categorias</h3>
                    <p>Cuando el administrador agregue categorias, apareceran aqui.</p>
                </article>
            @endforelse
        </div>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Destacados</p>
                <h2>Libros recientes del catalogo</h2>
            </div>
        </div>

        <div class="books-grid">
            @forelse ($featuredBooks as $book)
                <article class="book-card">
                    <div class="book-badge">{{ strtoupper(substr($book->title, 0, 1)) }}</div>
                    <p class="book-meta">{{ $book->author->full_name }}</p>
                    <h3>{{ $book->title }}</h3>
                    <p class="book-summary">{{ \Illuminate\Support\Str::limit($book->summary ?? 'Sin resumen disponible.', 110) }}</p>
                    <div class="book-footer">
                        <span class="status-chip">{{ $book->stock > 0 ? 'Disponible' : 'Sin stock' }}</span>
                        <a href="{{ route('catalog.show', $book) }}" class="text-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <article class="card">
                    <h3>Sin libros publicados</h3>
                    <p>El administrador aun no ha cargado libros al sistema.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection

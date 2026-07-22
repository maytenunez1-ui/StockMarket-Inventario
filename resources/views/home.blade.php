@extends('layouts.app')

@section('title', 'Supermercado UJCV')

@section('content')
    <section class="hero hero-panel">
        <div>
            <p class="eyebrow">Supermercado web funcional</p>
            <h1>Supermercado UJCV: productos frescos, compras e inventario en un solo lugar.</h1>
            <p class="hero-text">
                Los visitantes pueden descubrir el catalogo. Los usuarios registrados pueden solicitar compras.
                El administrador controla productos, marcas, categorias, proveedores, usuarios y estados de compra.
            </p>
            <div class="hero-actions">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Ver catalogo</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Crear cuenta</a>
                @else
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Mis compras</a>
                @endguest
            </div>
        </div>

        <div class="hero-media">
            <img src="{{ asset('images/supermarket-hero.png') }}" alt="Pasillo de supermercado con productos frescos">
            <div class="stats-grid">
                <article class="stat-card">
                    <span class="stat-value">{{ $stats['books'] }}</span>
                    <span class="stat-label">Productos activos</span>
                </article>
                <article class="stat-card">
                    <span class="stat-value">{{ $stats['users'] }}</span>
                    <span class="stat-label">Usuarios registrados</span>
                </article>
                <article class="stat-card">
                    <span class="stat-value">{{ $stats['loans'] }}</span>
                    <span class="stat-label">Compras activas</span>
                </article>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Categorias principales</p>
                <h2>Secciones para comprar rapido</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-link">Ver todo el catalogo</a>
        </div>

        <div class="grid-panel compact-grid">
            @forelse ($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="card card-link">
                    <p class="card-label">Categoria</p>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->books_count }} productos relacionados.</p>
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
                <h2>Productos recientes del catalogo</h2>
            </div>
        </div>

        <div class="books-grid">
            @forelse ($featuredBooks as $book)
                <article class="book-card">
                    <img class="product-image" src="{{ $book->imageUrl() }}" alt="{{ $book->title }}">
                    <p class="book-meta">{{ $book->author->full_name }}</p>
                    <h3>{{ $book->title }}</h3>
                    <p class="book-summary">{{ \Illuminate\Support\Str::limit($book->summary ?? 'Sin descripcion disponible.', 110) }}</p>
                    <div class="book-footer">
                        <span class="status-chip">{{ $book->stock > 0 ? 'Disponible' : 'Sin stock' }}</span>
                        <a href="{{ route('catalog.show', $book) }}" class="text-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <article class="card">
                    <h3>Sin productos publicados</h3>
                    <p>El administrador aun no ha cargado productos al sistema.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Catalogo de libros')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Catalogo</p>
                <h1 class="page-title">Explora el inventario disponible</h1>
            </div>
        </div>

        <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar card-surface">
            <div class="field">
                <label for="q">Buscar</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Titulo, autor, editorial o ISBN">
            </div>

            <div class="field">
                <label for="category">Categoria</label>
                <select id="category" name="category">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="books-grid">
            @forelse ($books as $book)
                <article class="book-card">
                    <div class="book-badge">{{ strtoupper(substr($book->title, 0, 1)) }}</div>
                    <p class="book-meta">{{ $book->author->full_name }}</p>
                    <h3>{{ $book->title }}</h3>
                    <p class="book-summary">{{ \Illuminate\Support\Str::limit($book->summary ?? 'Sin resumen disponible.', 120) }}</p>
                    <div class="tag-list">
                        @foreach ($book->categories->take(2) as $category)
                            <span class="tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    <div class="book-footer">
                        <span class="status-chip {{ $book->stock > 0 ? '' : 'is-muted' }}">{{ $book->stock > 0 ? 'Stock: ' . $book->stock : 'No disponible' }}</span>
                        <a href="{{ route('catalog.show', $book) }}" class="text-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    <h3>No encontramos libros con esos filtros.</h3>
                    <p>Ajusta la busqueda o limpia los filtros para ver mas resultados.</p>
                </article>
            @endforelse
        </div>

        @include('partials.pagination', ['paginator' => $books])
    </section>
@endsection

@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <section class="detail-layout">
        <article class="detail-card">
            <div class="detail-cover">
                <img src="{{ $book->imageUrl() }}" alt="{{ $book->title }}">
            </div>
            <div class="detail-copy">
                <p class="section-label">Detalle del producto</p>
                <h1 class="page-title">{{ $book->title }}</h1>
                <p class="detail-meta">{{ $book->author->full_name }} @if($book->publisher) - {{ $book->publisher->name }} @endif</p>
                <p class="hero-text">{{ $book->summary ?? 'Este producto aun no tiene descripcion registrada.' }}</p>

                <div class="detail-grid">
                    <div class="mini-card">
                        <span>Presentacion</span>
                        <strong>{{ ucfirst($book->format) }}</strong>
                    </div>
                    <div class="mini-card">
                        <span>Ingreso</span>
                        <strong>{{ $book->publication_year ?? 'N/D' }}</strong>
                    </div>
                    <div class="mini-card">
                        <span>Codigo</span>
                        <strong>{{ $book->isbn ?? 'N/D' }}</strong>
                    </div>
                    <div class="mini-card">
                        <span>Disponibilidad</span>
                        <strong>{{ $book->stock > 0 ? $book->stock . ' unidades' : 'Sin stock' }}</strong>
                    </div>
                </div>

                <div class="tag-list">
                    @foreach ($book->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                    @endforeach
                </div>

                <div class="hero-actions">
                    @auth
                        <form method="POST" action="{{ route('loans.store', $book) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" @disabled($book->stock < 1)>Comprar producto</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Inicia sesion para comprar</a>
                    @endauth
                    <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Volver al catalogo</a>
                </div>
            </div>
        </article>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Sugeridos</p>
                <h2>Productos relacionados</h2>
            </div>
        </div>

        <div class="books-grid">
            @forelse ($similarBooks as $similarBook)
                <article class="book-card">
                    <img class="product-image" src="{{ $similarBook->imageUrl() }}" alt="{{ $similarBook->title }}">
                    <p class="book-meta">{{ $similarBook->author->full_name }}</p>
                    <h3>{{ $similarBook->title }}</h3>
                    <p class="book-summary">{{ \Illuminate\Support\Str::limit($similarBook->summary ?? 'Sin descripcion disponible.', 90) }}</p>
                    <div class="book-footer">
                        <span class="status-chip">{{ $similarBook->stock > 0 ? 'Disponible' : 'Sin stock' }}</span>
                        <a href="{{ route('catalog.show', $similarBook) }}" class="text-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <article class="card">
                    <h3>Sin sugerencias por ahora</h3>
                    <p>Agrega mas productos a la misma categoria para ver recomendaciones.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection


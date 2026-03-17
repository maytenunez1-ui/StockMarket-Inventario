@extends('layouts.admin')

@section('title', 'Panel administrador')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Panel administrador</p>
                <h1 class="page-title">Resumen general de la biblioteca</h1>
            </div>
        </div>

        <div class="stats-grid stats-grid-admin">
            <article class="stat-card">
                <span class="stat-value">{{ $stats['books'] }}</span>
                <span class="stat-label">Libros</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['authors'] }}</span>
                <span class="stat-label">Autores</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['users'] }}</span>
                <span class="stat-label">Usuarios</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['active_loans'] }}</span>
                <span class="stat-label">Prestamos activos</span>
            </article>
        </div>
    </section>

    <section class="section-block two-column-grid">
        <article class="table-card">
            <div class="section-heading compact-heading">
                <div>
                    <p class="section-label">Ultimos libros</p>
                    <h2>Recien agregados</h2>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-link">Gestionar</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBooks as $book)
                        <tr>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author->full_name }}</td>
                            <td>{{ $book->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-cell">No hay libros cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </article>

        <article class="table-card">
            <div class="section-heading compact-heading">
                <div>
                    <p class="section-label">Prestamos</p>
                    <h2>Actividad reciente</h2>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="text-link">Ver todos</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLoans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td><span class="status-chip">{{ ucfirst($loan->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-cell">No hay prestamos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </article>
    </section>
@endsection

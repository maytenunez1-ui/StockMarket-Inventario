@extends('layouts.admin')

@section('title', 'Panel administrador')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Panel administrador</p>
                <h1 class="page-title">Resumen general del supermercado</h1>
            </div>
        </div>

        <div class="stats-grid stats-grid-admin">
            <article class="stat-card">
                <span class="stat-value">{{ $stats['books'] }}</span>
                <span class="stat-label">Productos</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['authors'] }}</span>
                <span class="stat-label">Marcas</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['users'] }}</span>
                <span class="stat-label">Usuarios</span>
            </article>
            <article class="stat-card">
                <span class="stat-value">{{ $stats['active_loans'] }}</span>
                <span class="stat-label">Compras activas</span>
            </article>
        </div>
    </section>

    <section class="section-block two-column-grid">
        <article class="table-card">
            <div class="section-heading compact-heading">
                <div>
                    <p class="section-label">Ultimos productos</p>
                    <h2>Recien agregados</h2>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-link">Gestionar</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Marca</th>
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
                            <td colspan="3" class="empty-cell">No hay productos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </article>

        <article class="table-card">
            <div class="section-heading compact-heading">
                <div>
                    <p class="section-label">Compras</p>
                    <h2>Actividad reciente</h2>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="text-link">Ver todos</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Producto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLoans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td><span class="status-chip">{{ ['pendiente' => 'Pendiente', 'prestado' => 'En preparacion', 'atrasado' => 'Retrasada', 'devuelto' => 'Entregada'][$loan->status] ?? ucfirst($loan->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-cell">No hay compras registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </article>
    </section>
@endsection

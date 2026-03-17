@extends('layouts.admin')

@section('title', 'Libros')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">CRUD</p>
                <h1 class="page-title">Libros</h1>
            </div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Nuevo libro</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Editorial</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author->full_name }}</td>
                            <td>{{ $book->publisher?->name ?? 'N/D' }}</td>
                            <td>{{ $book->stock }}</td>
                            <td><span class="status-chip">{{ $book->is_active ? 'Activo' : 'Oculto' }}</span></td>
                            <td class="actions-cell">
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-secondary btn-small">Editar</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-cell">No hay libros registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $books])
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'Autores')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">CRUD</p>
                <h1 class="page-title">Autores</h1>
            </div>
            <a href="{{ route('admin.authors.create') }}" class="btn btn-primary">Nuevo autor</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nacionalidad</th>
                        <th>Libros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($authors as $author)
                        <tr>
                            <td>{{ $author->full_name }}</td>
                            <td>{{ $author->nationality ?? 'N/D' }}</td>
                            <td>{{ $author->books_count }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-secondary btn-small">Editar</a>
                                <form method="POST" action="{{ route('admin.authors.destroy', $author) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-cell">No hay autores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $authors])
    </section>
@endsection

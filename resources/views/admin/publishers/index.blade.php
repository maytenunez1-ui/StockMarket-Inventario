@extends('layouts.admin')

@section('title', 'Editoriales')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">CRUD</p>
                <h1 class="page-title">Editoriales</h1>
            </div>
            <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">Nueva editorial</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Pais</th>
                        <th>Libros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publishers as $publisher)
                        <tr>
                            <td>{{ $publisher->name }}</td>
                            <td>{{ $publisher->country ?? 'N/D' }}</td>
                            <td>{{ $publisher->books_count }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-secondary btn-small">Editar</a>
                                <form method="POST" action="{{ route('admin.publishers.destroy', $publisher) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-cell">No hay editoriales registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $publishers])
    </section>
@endsection

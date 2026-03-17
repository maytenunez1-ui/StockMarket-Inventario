@extends('layouts.admin')

@section('title', 'Categorias')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">CRUD</p>
                <h1 class="page-title">Categorias</h1>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Nueva categoria</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Libros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->books_count }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary btn-small">Editar</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-cell">No hay categorias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $categories])
    </section>
@endsection

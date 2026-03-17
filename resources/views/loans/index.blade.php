@extends('layouts.app')

@section('title', 'Mis prestamos')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Mi actividad</p>
                <h1 class="page-title">Mis prestamos</h1>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Seguir explorando</a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Autor</th>
                        <th>Prestamo</th>
                        <th>Entrega</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ $loan->book->author->full_name }}</td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td><span class="status-chip">{{ ucfirst($loan->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-cell">Todavia no tienes prestamos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $loans])
    </section>
@endsection

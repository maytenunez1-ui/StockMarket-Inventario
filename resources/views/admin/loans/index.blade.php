@extends('layouts.admin')

@section('title', 'Prestamos')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Prestamos</p>
                <h1 class="page-title">Seguimiento de prestamos</h1>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Prestamo</th>
                        <th>Entrega</th>
                        <th>Gestion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.loans.update', $loan) }}" class="loan-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach (['pendiente', 'prestado', 'atrasado', 'devuelto'] as $status)
                                            <option value="{{ $status }}" @selected($loan->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="notes" value="{{ $loan->notes }}">
                                    <button type="submit" class="btn btn-secondary btn-small">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-cell">No hay prestamos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $loans])
    </section>
@endsection

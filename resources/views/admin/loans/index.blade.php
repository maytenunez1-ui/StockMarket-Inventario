@extends('layouts.admin')

@section('title', 'Compras')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Compras</p>
                <h1 class="page-title">Seguimiento de compras</h1>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Producto</th>
                        <th>Compra</th>
                        <th>Entrega estimada</th>
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
                                        @foreach (['pendiente' => 'Pendiente', 'prestado' => 'En preparacion', 'atrasado' => 'Retrasada', 'devuelto' => 'Entregada'] as $status => $label)
                                            <option value="{{ $status }}" @selected($loan->status === $status)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="notes" value="{{ $loan->notes }}">
                                    <button type="submit" class="btn btn-secondary btn-small">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-cell">No hay compras registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $loans])
    </section>
@endsection

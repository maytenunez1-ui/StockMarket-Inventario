@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Usuarios</p>
                <h1 class="page-title">Gestion de roles</h1>
            </div>
            <span class="status-chip">Admins activos: {{ $adminCount }}</span>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Compras</th>
                        <th>Rol</th>
                        <th>Actualizar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->loans_count }}</td>
                            <td><span class="status-chip">{{ $user->role }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role">
                                        <option value="user" @selected($user->role === 'user')>Usuario</option>
                                        <option value="admin" @selected($user->role === 'admin')>Administrador</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary btn-small">Guardar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-cell">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $users])
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
    <section class="auth-wrap">
        <div class="auth-card card-surface">
            <p class="section-label">Registro</p>
            <h1 class="page-title">Crear cuenta</h1>
            <p class="hero-text">Registrate para solicitar prestamos y seguir tu historial dentro de la biblioteca.</p>

            <form method="POST" action="{{ route('register') }}" class="form-stack">
                @csrf

                <div class="field">
                    <label for="name">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Correo electronico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Contrasena</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmar contrasena</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Crear cuenta</button>
            </form>
        </div>
    </section>
@endsection

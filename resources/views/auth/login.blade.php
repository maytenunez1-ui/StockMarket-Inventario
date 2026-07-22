@extends('layouts.app')

@section('title', 'Iniciar sesion')

@section('content')
    <section class="auth-wrap">
        <div class="auth-card card-surface">
            <p class="section-label">Acceso</p>
            <h1 class="page-title">Iniciar sesion</h1>
            <p class="hero-text">Ingresa con tu correo para acceder a compras, historial y panel de gestion si corresponde.</p>

            <form method="POST" action="{{ route('login') }}" class="form-stack">
                @csrf

                <div class="field">
                    <label for="email">Correo electronico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Contrasena</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <label class="checkbox-row">
                    <input type="checkbox" name="remember" value="1">
                    <span>Mantener sesion iniciada</span>
                </label>

                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
        </div>
    </section>
@endsection

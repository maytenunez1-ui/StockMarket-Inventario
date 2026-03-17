@extends('layouts.admin')

@section('title', 'Nuevo autor')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Autores</p>
                <h1 class="page-title">Crear autor</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.authors.store') }}" class="form-card card-surface">
            @csrf
            @include('admin.authors._form')
        </form>
    </section>
@endsection

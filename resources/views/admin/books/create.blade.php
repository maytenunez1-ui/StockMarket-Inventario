@extends('layouts.admin')

@section('title', 'Nuevo libro')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Libros</p>
                <h1 class="page-title">Crear libro</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.books.store') }}" class="form-card card-surface">
            @csrf
            @include('admin.books._form')
        </form>
    </section>
@endsection

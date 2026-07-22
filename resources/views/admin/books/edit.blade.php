@extends('layouts.admin')

@section('title', 'Editar producto')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Productos</p>
                <h1 class="page-title">Editar producto</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.books.update', $book) }}" class="form-card card-surface">
            @csrf
            @method('PUT')
            @include('admin.books._form')
        </form>
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'Nueva categoria')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Categorias</p>
                <h1 class="page-title">Crear categoria</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="form-card card-surface">
            @csrf
            @include('admin.categories._form')
        </form>
    </section>
@endsection

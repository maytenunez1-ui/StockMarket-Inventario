@extends('layouts.admin')

@section('title', 'Editar categoria')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Categorias</p>
                <h1 class="page-title">Editar categoria</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="form-card card-surface">
            @csrf
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'Editar marca')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Marcas</p>
                <h1 class="page-title">Editar marca</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="form-card card-surface">
            @csrf
            @method('PUT')
            @include('admin.authors._form')
        </form>
    </section>
@endsection

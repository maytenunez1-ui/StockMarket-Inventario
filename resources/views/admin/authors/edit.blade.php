@extends('layouts.admin')

@section('title', 'Editar autor')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Autores</p>
                <h1 class="page-title">Editar autor</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="form-card card-surface">
            @csrf
            @method('PUT')
            @include('admin.authors._form')
        </form>
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'Nueva marca')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Marcas</p>
                <h1 class="page-title">Crear marca</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.authors.store') }}" class="form-card card-surface">
            @csrf
            @include('admin.authors._form')
        </form>
    </section>
@endsection

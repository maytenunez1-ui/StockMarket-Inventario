@extends('layouts.admin')

@section('title', 'Nueva editorial')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Editoriales</p>
                <h1 class="page-title">Crear editorial</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.publishers.store') }}" class="form-card card-surface">
            @csrf
            @include('admin.publishers._form')
        </form>
    </section>
@endsection

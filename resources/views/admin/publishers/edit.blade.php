@extends('layouts.admin')

@section('title', 'Editar proveedor')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <p class="section-label">Proveedores</p>
                <h1 class="page-title">Editar proveedor</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.publishers.update', $publisher) }}" class="form-card card-surface">
            @csrf
            @method('PUT')
            @include('admin.publishers._form')
        </form>
    </section>
@endsection

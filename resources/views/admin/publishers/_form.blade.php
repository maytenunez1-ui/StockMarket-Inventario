<div class="form-grid">
    <div class="field">
        <label for="name">Nombre</label>
        <input id="name" type="text" name="name" value="{{ old('name', $publisher->name ?? '') }}" required>
    </div>

    <div class="field">
        <label for="country">Pais</label>
        <input id="country" type="text" name="country" value="{{ old('country', $publisher->country ?? '') }}">
    </div>
</div>

<div class="field">
    <label for="website">Sitio web</label>
    <input id="website" type="url" name="website" value="{{ old('website', $publisher->website ?? '') }}">
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar proveedor</button>
    <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

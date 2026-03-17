<div class="field">
    <label for="name">Nombre</label>
    <input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required>
</div>

<div class="field">
    <label for="description">Descripcion</label>
    <textarea id="description" name="description" rows="5">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar categoria</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

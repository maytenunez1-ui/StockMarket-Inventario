<div class="form-grid">
    <div class="field">
        <label for="full_name">Nombre de la marca</label>
        <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $author->full_name ?? '') }}" required>
    </div>

    <div class="field">
        <label for="nationality">Origen</label>
        <input id="nationality" type="text" name="nationality" value="{{ old('nationality', $author->nationality ?? '') }}">
    </div>

    <div class="field">
        <label for="birth_date">Fecha de registro</label>
        <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', isset($author) && $author->birth_date ? $author->birth_date->format('Y-m-d') : '') }}">
    </div>
</div>

<div class="field">
    <label for="biography">Descripcion</label>
    <textarea id="biography" name="biography" rows="6">{{ old('biography', $author->biography ?? '') }}</textarea>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar marca</button>
    <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

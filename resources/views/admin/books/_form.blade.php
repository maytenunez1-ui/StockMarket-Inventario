<div class="form-grid">
    <div class="field">
        <label for="title">Producto</label>
        <input id="title" type="text" name="title" value="{{ old('title', $book->title ?? '') }}" required>
    </div>

    <div class="field">
        <label for="author_id">Marca</label>
        <select id="author_id" name="author_id" required>
            <option value="">Selecciona una marca</option>
            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected((string) old('author_id', $book->author_id ?? '') === (string) $author->id)>{{ $author->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="publisher_id">Proveedor</label>
        <select id="publisher_id" name="publisher_id">
            <option value="">Sin proveedor</option>
            @foreach ($publishers as $publisher)
                <option value="{{ $publisher->id }}" @selected((string) old('publisher_id', $book->publisher_id ?? '') === (string) $publisher->id)>{{ $publisher->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="isbn">Codigo</label>
        <input id="isbn" type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
    </div>

    <div class="field">
        <label for="publication_year">Ano de ingreso</label>
        <input id="publication_year" type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year ?? '') }}">
    </div>

    <div class="field">
        <label for="format">Presentacion</label>
        <select id="format" name="format" required>
            @foreach (['fisico' => 'Unidad', 'digital' => 'Caja', 'hibrido' => 'Paquete'] as $value => $label)
                <option value="{{ $value }}" @selected(old('format', $book->format ?? 'hibrido') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="stock">Stock</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $book->stock ?? 1) }}" required>
    </div>
</div>

<div class="field">
    <label for="summary">Descripcion</label>
    <textarea id="summary" name="summary" rows="6">{{ old('summary', $book->summary ?? '') }}</textarea>
</div>

<div class="field">
    <span class="input-label">Categorias</span>
    <div class="checkbox-grid">
        @php
            $selectedCategories = old('categories', isset($book) ? $book->categories->pluck('id')->all() : []);
        @endphp
        @foreach ($categories as $category)
            <label class="checkbox-row">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))>
                <span>{{ $category->name }}</span>
            </label>
        @endforeach
    </div>
</div>

<label class="checkbox-row">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $book->is_active ?? true))>
    <span>Producto visible en el catalogo publico</span>
</label>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar producto</button>
    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

<div class="form-grid">
    <div class="field">
        <label for="title">Titulo</label>
        <input id="title" type="text" name="title" value="{{ old('title', $book->title ?? '') }}" required>
    </div>

    <div class="field">
        <label for="author_id">Autor</label>
        <select id="author_id" name="author_id" required>
            <option value="">Selecciona un autor</option>
            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected((string) old('author_id', $book->author_id ?? '') === (string) $author->id)>{{ $author->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="publisher_id">Editorial</label>
        <select id="publisher_id" name="publisher_id">
            <option value="">Sin editorial</option>
            @foreach ($publishers as $publisher)
                <option value="{{ $publisher->id }}" @selected((string) old('publisher_id', $book->publisher_id ?? '') === (string) $publisher->id)>{{ $publisher->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label for="isbn">ISBN</label>
        <input id="isbn" type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
    </div>

    <div class="field">
        <label for="publication_year">Ano de publicacion</label>
        <input id="publication_year" type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year ?? '') }}">
    </div>

    <div class="field">
        <label for="format">Formato</label>
        <select id="format" name="format" required>
            @foreach (['fisico' => 'Fisico', 'digital' => 'Digital', 'hibrido' => 'Hibrido'] as $value => $label)
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
    <label for="summary">Resumen</label>
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
    <span>Libro visible en el catalogo publico</span>
</label>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar libro</button>
    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

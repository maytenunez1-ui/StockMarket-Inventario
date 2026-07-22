<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::query()
            ->with(['author', 'publisher', 'categories'])
            ->latest()
            ->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'authors' => Author::orderBy('full_name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $validated['slug'] = $this->makeUniqueSlug($validated['title']);

        $book = Book::create($validated);
        $book->categories()->sync($categories);

        return redirect()->route('admin.books.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Book $book): View
    {
        $book->load('categories');

        return view('admin.books.edit', [
            'book' => $book,
            'authors' => Author::orderBy('full_name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $this->validatedData($request, $book);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $validated['slug'] = $this->makeUniqueSlug($validated['title'], $book);

        $book->update($validated);
        $book->categories()->sync($categories);

        return redirect()->route('admin.books.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        try {
            $book->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar este producto porque tiene compras asociadas.');
        }

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    private function validatedData(Request $request, ?Book $book = null): array
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($book?->id)],
            'publication_year' => ['nullable', 'integer', 'min:1500', 'max:' . (date('Y') + 1)],
            'format' => ['required', Rule::in(['fisico', 'digital', 'hibrido'])],
            'stock' => ['required', 'integer', 'min:0'],
            'summary' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function makeUniqueSlug(string $title, ?Book $book = null): string
    {
        $baseSlug = Str::slug($title);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'producto';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Book::query()
                ->where('slug', $slug)
                ->when($book, fn ($query) => $query->where('id', '!=', $book->id))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

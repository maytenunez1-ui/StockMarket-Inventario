<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(): View
    {
        $authors = Author::query()
            ->withCount('books')
            ->orderBy('full_name')
            ->paginate(10);

        return view('admin.authors.index', compact('authors'));
    }

    public function create(): View
    {
        return view('admin.authors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Author::create($this->validatedData($request));

        return redirect()->route('admin.authors.index')->with('success', 'Marca creada correctamente.');
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $author->update($this->validatedData($request));

        return redirect()->route('admin.authors.index')->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Author $author): RedirectResponse
    {
        try {
            $author->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar esta marca porque tiene productos asociados.');
        }

        return back()->with('success', 'Marca eliminada correctamente.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'birth_date' => ['nullable', 'date'],
            'biography' => ['nullable', 'string'],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublisherController extends Controller
{
    public function index(): View
    {
        $publishers = Publisher::query()
            ->withCount('books')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.publishers.index', compact('publishers'));
    }

    public function create(): View
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['slug'] = $this->makeUniqueSlug($validated['name']);

        Publisher::create($validated);

        return redirect()->route('admin.publishers.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Publisher $publisher): View
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['slug'] = $this->makeUniqueSlug($validated['name'], $publisher);

        $publisher->update($validated);

        return redirect()->route('admin.publishers.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Publisher $publisher): RedirectResponse
    {
        try {
            $publisher->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar este proveedor porque tiene productos asociados.');
        }

        return back()->with('success', 'Proveedor eliminado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);
    }

    private function makeUniqueSlug(string $name, ?Publisher $publisher = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'proveedor';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Publisher::query()
                ->where('slug', $slug)
                ->when($publisher, fn ($query) => $query->where('id', '!=', $publisher->id))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

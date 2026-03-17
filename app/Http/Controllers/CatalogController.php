<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $selectedCategory = trim((string) $request->string('category'));

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $books = Book::query()
            ->with(['author', 'publisher', 'categories'])
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhereHas('author', fn ($authorQuery) => $authorQuery->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('publisher', fn ($publisherQuery) => $publisherQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->where('slug', $selectedCategory));
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('catalog.index', compact('books', 'categories', 'search', 'selectedCategory'));
    }

    public function show(Book $book): View
    {
        abort_unless($book->is_active || (auth()->check() && auth()->user()->isAdmin()), 404);

        $book->load(['author', 'publisher', 'categories']);

        $categoryIds = $book->categories->pluck('id');

        $similarBooks = Book::query()
            ->with('author')
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->when(
                $categoryIds->isNotEmpty(),
                fn ($query) => $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds))
            )
            ->latest()
            ->take(3)
            ->get();

        return view('catalog.show', compact('book', 'similarBooks'));
    }
}

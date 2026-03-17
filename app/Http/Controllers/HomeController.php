<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredBooks = Book::query()
            ->with(['author', 'categories'])
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::query()
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take(4)
            ->get();

        $stats = [
            'books' => Book::where('is_active', true)->count(),
            'users' => User::count(),
            'loans' => Loan::whereIn('status', ['prestado', 'atrasado'])->count(),
        ];

        return view('home', compact('featuredBooks', 'categories', 'stats'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'books' => Book::count(),
            'authors' => Author::count(),
            'users' => User::count(),
            'active_loans' => Loan::whereIn('status', ['prestado', 'atrasado'])->count(),
        ];

        $recentLoans = Loan::query()
            ->with(['book', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $recentBooks = Book::query()
            ->with('author')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLoans', 'recentBooks'));
    }
}

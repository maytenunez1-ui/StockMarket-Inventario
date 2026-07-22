<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $loans = $request->user()
            ->loans()
            ->with('book.author')
            ->latest()
            ->paginate(10);

        return view('loans.index', compact('loans'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        if (! $book->is_active || $book->stock < 1) {
            return back()->with('error', 'Este producto no se encuentra disponible en este momento.');
        }

        $alreadyBorrowed = $request->user()
            ->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['prestado', 'atrasado'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Ya tienes una compra pendiente para este producto.');
        }

        Loan::create([
            'book_id' => $book->id,
            'user_id' => $request->user()->id,
            'loan_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(2),
            'status' => 'prestado',
            'notes' => 'Compra solicitada desde el catalogo.',
        ]);

        $book->decrement('stock');

        return redirect()
            ->route('loans.index')
            ->with('success', 'Compra registrada. Puedes revisar el estado en Mis compras.');
    }
}

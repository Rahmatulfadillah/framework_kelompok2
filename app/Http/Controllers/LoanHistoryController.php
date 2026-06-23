<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;

class LoanHistoryController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    public function index()
    {
        $user = Auth::user();
        $loans = $this->loanService->getLoanHistory($user->id);

        return view('loans.history', compact('loans'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Gather stats
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $activeLoans = Loan::where('status', 'borrowed')->count();
        $returnedLoans = Loan::where('status', 'returned')->count();

        // 2. Fetch recent activities (mapped from recent loans)
        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get();

        $recentActivities = $recentLoans->map(function ($loan) {
            $isReturned = $loan->status === 'returned';

            return (object) [
                'icon' => $isReturned ? 'check-circle' : 'exchange-alt',
                'color' => $isReturned ? 'green' : 'blue',
                'title' => $isReturned ? 'Buku Dikembalikan' : 'Buku Dipinjam',
                'description' => $loan->user->name.($isReturned ? ' mengembalikan ' : ' meminjam ').'"'.$loan->book->judul.'"',
                'time' => $loan->created_at ? $loan->created_at->diffForHumans() : $loan->loan_date->diffForHumans(),
            ];
        });

        // 3. Gather Chart.js data (loan counts for the past 7 days)
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartValues[] = Loan::whereDate('loan_date', $date)->count();
        }

        // 4. Gather recent loans list (table view)
        $recentOrders = Loan::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($loan) {
                return (object) [
                    'id' => '#'.$loan->id,
                    'customer' => $loan->user->name,
                    'product' => $loan->book->judul,
                    'status' => $loan->status === 'returned' ? 'Returned' : 'Borrowed',
                    'total' => $loan->loan_date->format('d/m/Y'),
                ];
            });

        // If User is not admin, show simpler dashboard or redirect
        if (auth()->user()->isUser()) {
            $user = auth()->user();
            $myActiveLoans = Loan::where('user_id', $user->id)->where('status', 'borrowed')->count();
            $myReturnedLoans = Loan::where('user_id', $user->id)->where('status', 'returned')->count();
            
            $myRecentLoans = Loan::with(['book'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($loan) {
                    return (object) [
                        'id' => '#'.$loan->id,
                        'product' => $loan->book->judul,
                        'status' => $loan->status === 'returned' ? 'Returned' : 'Borrowed',
                        'total' => $loan->loan_date->format('d/m/Y'),
                    ];
                });

            return view('user_dashboard', [
                'activeLoans' => $myActiveLoans,
                'returnedLoans' => $myReturnedLoans,
                'recentOrders' => $myRecentLoans,
            ]);
        }

        // Admin Dashboard Data
        return view('dashboard', [
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'activeLoans' => $activeLoans,
            'returnedLoans' => $returnedLoans,
            'recentActivities' => $recentActivities,
            'recentOrders' => $recentOrders,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }
}

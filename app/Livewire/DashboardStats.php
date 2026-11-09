<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $rentals = $user->rentals();

            return view('livewire.dashboard-stats', [
                'isAdmin' => false,
                'myPendingRentals' => (clone $rentals)->where('status', 'pending')->count(),
                'myApprovedRentals' => (clone $rentals)->where('status', 'approved')->count(),
                'myCompletedRentals' => (clone $rentals)->where('status', 'completed')->count(),
                'myTotalSpent' => (clone $rentals)
                    ->whereIn('status', ['approved', 'completed'])
                    ->sum('subtotal'),
            ]);
        }

        return view('livewire.dashboard-stats', [
            'isAdmin' => true,
            'categoryCount' => Category::count(),
            'itemCount' => Item::count(),
            'availableStock' => Item::sum('stock'),
            'pendingRentals' => Rental::where('status', 'pending')->count(),
            'approvedRentals' => Rental::where('status', 'approved')->count(),
            'completedRentals' => Rental::where('status', 'completed')->count(),
            'totalRevenue' => Rental::whereIn('status', [
                'approved',
                'completed',
            ])->sum('subtotal'),
        ]);
    }
}

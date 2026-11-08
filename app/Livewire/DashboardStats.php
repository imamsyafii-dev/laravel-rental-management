<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\Rental;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        return view('livewire.dashboard-stats', [
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
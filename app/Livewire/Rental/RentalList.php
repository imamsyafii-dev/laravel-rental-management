<?php

namespace App\Livewire\Rental;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RentalList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.rental.rental-list', [
            'rentals' => Auth::user()
                ->rentals()
                ->with('rentalItems.item')
                ->latest()
                ->paginate(10),
        ]);
    }
}
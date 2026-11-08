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

    public function cancel(int $id): void
    {
        $rental = Auth::user()
            ->rentals()
            ->whereKey($id)
            ->firstOrFail();

        if ($rental->status !== 'pending') {
            session()->flash(
                'error',
                'Hanya rental pending yang dapat dibatalkan.'
            );

            return;
        }

        $rental->update([
            'status' => 'cancelled',
        ]);

        session()->flash(
            'success',
            'Pengajuan rental berhasil dibatalkan.'
        );
    }
}
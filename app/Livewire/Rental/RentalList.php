<?php

namespace App\Livewire\Rental;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RentalList extends Component
{
    use WithFileUploads;
    use WithPagination;

    public ?int $paymentRentalId = null;

    public $paymentProof;

    public function uploadPaymentProof(int $rentalId): void
    {
        $this->validate([
            'paymentProof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);

        $rental = Auth::user()
            ->rentals()
            ->whereKey($rentalId)
            ->firstOrFail();

        if ($rental->payment_status === 'paid') {
            session()->flash(
                'error',
                'Pembayaran rental ini sudah dikonfirmasi.'
            );

            return;
        }

        $path = $this->paymentProof->store(
            'payment-proofs',
            'public'
        );

        $rental->update([
            'payment_status' => 'submitted',
            'payment_proof_path' => $path,
        ]);

        $this->reset([
            'paymentRentalId',
            'paymentProof',
        ]);

        session()->flash(
            'success',
            'Bukti pembayaran berhasil diupload.'
        );
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
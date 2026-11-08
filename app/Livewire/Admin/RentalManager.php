<?php

namespace App\Livewire\Admin;

use App\Models\Rental;
use App\Notifications\RentalStatusUpdated;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class RentalManager extends Component
{
    use WithPagination;

    public function approve(int $id): void
    {
        DB::transaction(function () use ($id) {
            $rental = Rental::query()
                ->with('rentalItems.item')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($rental->status !== 'pending') {
                throw new RuntimeException(
                    'Rental ini sudah dibatalkan atau diproses sebelumnya.'
                );
            }

            foreach ($rental->rentalItems as $rentalItem) {
                $item = $rentalItem->item;

                if ($rentalItem->quantity > $item->stock) {
                    throw new RuntimeException(
                        "Stok {$item->name} tidak mencukupi."
                    );
                }

                $item->decrement('stock', $rentalItem->quantity);
            }

            $rental->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            $rental->user->notify(new RentalStatusUpdated($rental));
        });

        session()->flash(
            'success',
            'Rental berhasil disetujui dan stok telah dikurangi.'
        );
    }

    public function reject(int $id): void
    {
        $rental = Rental::findOrFail($id);

        if ($rental->status !== 'pending') {
            session()->flash(
                'error',
                'Rental ini sudah diproses sebelumnya.'
            );

            return;
        }

        $rental->update([
            'status' => 'rejected',
        ]);
        
        $rental->user->notify(new RentalStatusUpdated($rental));

        session()->flash(
            'success',
            'Rental berhasil ditolak.'
        );
    }

    public function confirmPayment(int $id): void
    {
        $rental = Rental::findOrFail($id);

        if ($rental->payment_status !== 'submitted') {
            session()->flash(
                'error',
                'Belum ada bukti pembayaran yang perlu dikonfirmasi.'
            );

            return;
        }

        $rental->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        session()->flash(
            'success',
            'Pembayaran berhasil dikonfirmasi.'
        );
    }

    public function rejectPayment(int $id): void
    {
        $rental = Rental::findOrFail($id);

        if ($rental->payment_status !== 'submitted') {
            session()->flash(
                'error',
                'Belum ada bukti pembayaran yang perlu ditolak.'
            );

            return;
        }

        $rental->update([
            'payment_status' => 'rejected',
        ]);

        session()->flash(
            'success',
            'Bukti pembayaran ditolak.'
        );
    }

    public function render()
    {
        return view('livewire.admin.rental-manager', [
            'rentals' => Rental::query()
                ->with(['user', 'rentalItems.item'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function complete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $rental = Rental::query()
                ->with('rentalItems.item')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($rental->status !== 'approved') {
                throw new RuntimeException(
                    'Hanya rental yang disetujui yang dapat diselesaikan.'
                );
            }

            foreach ($rental->rentalItems as $rentalItem) {
                $rentalItem->item->increment(
                    'stock',
                    $rentalItem->quantity
                );
            }

            $rental->update([
                'status' => 'completed',
                'returned_at' => now(),
            ]);
        });

        session()->flash(
            'success',
            'Rental selesai dan stok barang telah dikembalikan.'
        );
    }
}

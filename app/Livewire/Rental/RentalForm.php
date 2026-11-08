<?php

namespace App\Livewire\Rental;

use App\Models\Item;
use App\Services\RentalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RentalForm extends Component
{
    public string $startDate = '';

    public string $endDate = '';

    public ?int $itemId = null;

    public int $quantity = 1;

    public string $notes = '';

    public array $selectedItems = [];

    public function addItem(): void
    {
        $this->validate([
            'itemId' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = Item::whereKey($this->itemId)
            ->where('is_active', true)
            ->firstOrFail();

        if ($this->quantity > $item->stock) {
            throw ValidationException::withMessages([
                'quantity' => "Stok {$item->name} hanya tersedia {$item->stock}.",
            ]);
        }

        $this->selectedItems[$item->id] = [
            'item_id' => $item->id,
            'name' => $item->name,
            'quantity' => $this->quantity,
            'price_per_day' => (float) $item->price_per_day,
        ];

        $this->reset(['itemId', 'quantity']);
        $this->quantity = 1;
    }

    public function removeItem(int $itemId): void
    {
        unset($this->selectedItems[$itemId]);
    }

    public function submit(RentalService $rentalService): void
    {
        $this->validate([
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (empty($this->selectedItems)) {
            throw ValidationException::withMessages([
                'selectedItems' => 'Pilih minimal satu barang.',
            ]);
        }

        $rentalService->create(
            user: Auth::user(),
            startDate: $this->startDate,
            endDate: $this->endDate,
            items: array_values($this->selectedItems),
            notes: $this->notes ?: null,
        );

        session()->flash(
            'success',
            'Pengajuan penyewaan berhasil dibuat.'
        );

        $this->reset([
            'startDate',
            'endDate',
            'notes',
            'selectedItems',
        ]);

        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.rental.rental-form', [
            'items' => Item::query()
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get(),
        ]);
    }
}
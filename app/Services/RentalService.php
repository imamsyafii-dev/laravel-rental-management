<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class RentalService
{
    public function create(
        User $user,
        string $startDate,
        string $endDate,
        array $items,
        ?string $notes = null,
    ): Rental {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            throw new InvalidArgumentException(
                'Tanggal selesai tidak boleh sebelum tanggal mulai.'
            );
        }

        $rentalDays = $start->diffInDays($end) + 1;

        return DB::transaction(function () use (
            $user,
            $start,
            $end,
            $items,
            $notes,
            $rentalDays
        ) {
            $subtotal = 0;
            $rentalItems = [];

            foreach ($items as $itemData) {
                $item = Item::query()
                    ->whereKey($itemData['item_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $itemData['quantity'];

                if ($quantity < 1) {
                    throw new InvalidArgumentException(
                        'Jumlah barang harus minimal 1.'
                    );
                }

                if ($quantity > $item->stock) {
                    throw new InvalidArgumentException(
                        "Stok {$item->name} tidak mencukupi."
                    );
                }

                $itemSubtotal = $item->price_per_day
                    * $quantity
                    * $rentalDays;

                $subtotal += $itemSubtotal;

                $rentalItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'price_per_day' => $item->price_per_day,
                    'rental_days' => $rentalDays,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $rental = Rental::create([
                'user_id' => $user->id,
                'rental_code' => 'RNT-' . Str::upper(Str::random(10)),
                'start_date' => $start,
                'end_date' => $end,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'deposit' => 0,
                'notes' => $notes,
            ]);

            $rental->rentalItems()->createMany($rentalItems);

            return $rental->load('rentalItems.item');
        });
    }
}
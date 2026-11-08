<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'item_id',
        'quantity',
        'price_per_day',
        'rental_days',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price_per_day' => 'decimal:2',
            'rental_days' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Services\RentalService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a rental with rental items', function () {
    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Kamera',
        'slug' => 'kamera',
        'description' => null,
        'is_active' => true,
    ]);

    $item = Item::create([
        'category_id' => $category->id,
        'name' => 'Kamera Canon',
        'slug' => 'kamera-canon',
        'description' => 'Kamera untuk dokumentasi',
        'price_per_day' => 150000,
        'stock' => 2,
        'is_active' => true,
    ]);

    $rental = app(RentalService::class)->create(
        user: $user,
        startDate: '2026-12-01',
        endDate: '2026-12-03',
        items: [
            [
                'item_id' => $item->id,
                'quantity' => 1,
            ],
        ],
        notes: 'Test rental',
    );

    expect($rental->status)->toBe('pending')
        ->and($rental->rentalItems)->toHaveCount(1)
        ->and((float) $rental->subtotal)->toBe(450000.0);
});

it('rejects a rental when the end date is before the start date', function () {
    $user = User::factory()->create();

    expect(fn () => app(RentalService::class)->create(
        user: $user,
        startDate: '2026-12-05',
        endDate: '2026-12-01',
        items: [],
    ))->toThrow(InvalidArgumentException::class);
});
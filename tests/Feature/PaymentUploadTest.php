<?php

use App\Livewire\Rental\RentalList;
use App\Models\Category;
use App\Models\Item;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('allows a customer to upload payment proof', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Kamera',
        'slug' => 'kamera',
        'is_active' => true,
    ]);

    $item = Item::create([
        'category_id' => $category->id,
        'name' => 'Kamera Canon',
        'slug' => 'kamera-canon',
        'price_per_day' => 150000,
        'stock' => 2,
        'is_active' => true,
    ]);

    $rental = Rental::create([
        'user_id' => $user->id,
        'rental_code' => 'RNT-TEST001',
        'start_date' => '2026-12-01',
        'end_date' => '2026-12-03',
        'status' => 'pending',
        'subtotal' => 450000,
        'deposit' => 0,
    ]);

    $rental->rentalItems()->create([
        'item_id' => $item->id,
        'quantity' => 1,
        'price_per_day' => 150000,
        'rental_days' => 3,
        'subtotal' => 450000,
    ]);

    $file = UploadedFile::fake()->create(
        'payment-proof.pdf',
        500,
        'application/pdf'
    );

    Livewire::actingAs($user)
        ->test(RentalList::class)
        ->set('paymentProof', $file)
        ->call('uploadPaymentProof', $rental->id)
        ->assertHasNoErrors();

    expect($rental->fresh()->payment_status)->toBe('submitted');

    Storage::disk('public')->assertExists(
        $rental->fresh()->payment_proof_path
    );
});
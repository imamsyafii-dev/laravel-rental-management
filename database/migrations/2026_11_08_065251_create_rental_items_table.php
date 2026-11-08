<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('rental_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_id')
            ->constrained()
            ->cascadeOnDelete();
        $table->foreignId('item_id')
            ->constrained()
            ->restrictOnDelete();
        $table->unsignedInteger('quantity');
        $table->decimal('price_per_day', 12, 2);
        $table->unsignedInteger('rental_days');
        $table->decimal('subtotal', 12, 2);
        $table->timestamps();

        $table->unique(['rental_id', 'item_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};

<div class="space-y-6">
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($rentals as $rental)
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $rental->rental_code }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ $rental->start_date->format('d/m/Y') }}
                        -
                        {{ $rental->end_date->format('d/m/Y') }}
                    </p>
                </div>

                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'completed' => 'bg-blue-100 text-blue-800',
                        'cancelled' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp

                <span class="{{ $statusClasses[$rental->status] ?? 'bg-gray-100 text-gray-800' }} rounded-full px-3 py-1 text-sm font-medium">
                    {{ ucfirst($rental->status) }}
                </span>
            </div>

            <div class="mt-4 border-t pt-4">
                <h4 class="font-medium text-gray-900">
                    Barang
                </h4>

                <ul class="mt-2 space-y-2">
                    @foreach ($rental->rentalItems as $rentalItem)
                        <li class="flex justify-between text-sm text-gray-600">
                            <span>
                                {{ $rentalItem->item->name }}
                                × {{ $rentalItem->quantity }}
                            </span>

                            <span>
                                Rp {{ number_format($rentalItem->subtotal, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4 flex justify-between border-t pt-4">
                <span class="font-medium text-gray-900">
                    Total
                </span>

                <span class="font-semibold text-gray-900">
                    Rp {{ number_format($rental->subtotal, 0, ',', '.') }}
                </span>
            </div>

            @if ($rental->status === 'pending')
                <div class="mt-4 border-t pt-4">
                    <button
                        type="button"
                        wire:click="cancel({{ $rental->id }})"
                        wire:confirm="Batalkan pengajuan rental ini?"
                        style="display: inline-block !important; background-color: #dc2626 !important; color: #ffffff !important;"
                        class="rounded-md px-4 py-2 text-sm font-semibold"
                    >
                        Batalkan Rental
                    </button>
                </div>
            @endif

            @if ($rental->notes)
                <div class="mt-4 rounded-md bg-gray-50 p-3 text-sm text-gray-600">
                    <strong>Catatan:</strong>
                    {{ $rental->notes }}
                </div>
            @endif
        </div>
    @empty
        <div class="rounded-lg bg-white p-6 text-center text-gray-500 shadow">
            Anda belum memiliki penyewaan.
        </div>
    @endforelse

    <div>
        {{ $rentals->links() }}
    </div>
</div>
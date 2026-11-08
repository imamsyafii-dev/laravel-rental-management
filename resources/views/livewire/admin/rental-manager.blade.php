<div wire:poll.10s class="space-y-6">
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
            <div class="flex flex-col justify-between gap-4 md:flex-row">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $rental->rental_code }}
                    </h3>

                    <p class="text-sm text-gray-600">
                        Pemesan: {{ $rental->user->name }}
                    </p>

                    <p class="text-sm text-gray-600">
                        {{ $rental->start_date->format('d/m/Y') }}
                        -
                        {{ $rental->end_date->format('d/m/Y') }}
                    </p>
                </div>

                <span class="h-fit rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">
                    {{ ucfirst($rental->status) }}
                </span>
            </div>

            <div class="mt-4 border-t pt-4">
                <h4 class="font-medium text-gray-900">
                    Barang Rental
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

            <div class="mt-4 border-t pt-4">
                <p class="text-sm text-gray-600">
                    Status pembayaran:
                    <span class="font-medium">
                        {{ ucfirst($rental->payment_status) }}
                    </span>
                </p>

                @if ($rental->payment_proof_path)
                    <a
                        href="{{ asset('storage/' . $rental->payment_proof_path) }}"
                        target="_blank"
                        class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Lihat Bukti Pembayaran
                    </a>
                @endif

                @if ($rental->payment_status === 'submitted')
                    <div class="mt-3">
                        <button
                            type="button"
                            wire:click="confirmPayment({{ $rental->id }})"
                            wire:confirm="Konfirmasi pembayaran ini?"
                            style="display: inline-block !important; background-color: #16a34a !important; color: #ffffff !important;"
                            class="rounded-md px-4 py-2 text-sm font-semibold"
                        >
                            Konfirmasi Pembayaran
                        </button>

                        <button
                            type="button"
                            wire:click="rejectPayment({{ $rental->id }})"
                            wire:confirm="Tolak bukti pembayaran ini?"
                            style="display: inline-block !important; background-color: #dc2626 !important; color: #ffffff !important;"
                            class="ml-2 rounded-md px-4 py-2 text-sm font-semibold"
                        >
                            Tolak Pembayaran
                        </button>
                    </div>
                @endif
            </div>

            <div class="mt-4 flex items-center justify-between border-t pt-4">
                <span class="font-semibold text-gray-900">
                    Total:
                    Rp {{ number_format($rental->subtotal, 0, ',', '.') }}
                </span>

                @if ($rental->status === 'pending')
                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="approve({{ $rental->id }})"
                            wire:confirm="Setujui rental ini?"
                            style="display: inline-block !important; background-color: #16a34a !important; color: #ffffff !important;"
                            class="rounded-md px-4 py-2 text-sm font-semibold"
                        >
                            Setujui
                        </button>

                        <button
                            type="button"
                            wire:click="reject({{ $rental->id }})"
                            wire:confirm="Tolak rental ini?"
                            style="display: inline-block !important; background-color: #dc2626 !important; color: #ffffff !important;"
                            class="rounded-md px-4 py-2 text-sm font-semibold"
                        >
                            Tolak
                        </button>
                    </div>
                @elseif ($rental->status === 'approved')
                    <button
                        type="button"
                        wire:click="complete({{ $rental->id }})"
                        wire:confirm="Tandai barang sudah dikembalikan?"
                        style="display: inline-block !important; background-color: #2563eb !important; color: #ffffff !important;"
                        class="rounded-md px-4 py-2 text-sm font-semibold"
                    >
                        Tandai Selesai
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="rounded-lg bg-white p-6 text-center text-gray-500 shadow">
            Belum ada pengajuan rental.
        </div>
    @endforelse

    <div>
        {{ $rentals->links() }}
    </div>
</div>

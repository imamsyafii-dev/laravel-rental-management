<div class="space-y-6">
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg bg-white p-6 shadow">
        <h3 class="mb-6 text-lg font-semibold text-gray-900">
            Ajukan Penyewaan
        </h3>

        <form wire:submit="submit" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700">
                        Tanggal mulai
                    </label>

                    <input
                        id="startDate"
                        type="date"
                        wire:model="startDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('startDate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700">
                        Tanggal selesai
                    </label>

                    <input
                        id="endDate"
                        type="date"
                        wire:model="endDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('endDate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t pt-4">
                <h4 class="mb-3 font-medium text-gray-900">
                    Tambahkan Barang
                </h4>

                <div class="space-y-4">
                    <select
                        wire:model="itemId"
                        class="block w-full rounded-md border-gray-300 shadow-sm"
                    >
                        <option value="">Pilih barang</option>

                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }} — Rp {{ number_format($item->price_per_day, 0, ',', '.') }}/hari
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="number"
                        wire:model="quantity"
                        min="1"
                        class="block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Jumlah"
                    >

                    <button
                        type="button"
                        wire:click="addItem"
                        style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; background-color: #374151 !important; color: #ffffff !important;"
                        class="rounded-md px-4 py-2 text-sm font-semibold"
                    >
                        Tambah Barang
                    </button>
                </div>

                @error('itemId')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('quantity')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('selectedItems')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if (count($selectedItems) > 0)
                <div class="border-t pt-4">
                    <h4 class="mb-3 font-medium text-gray-900">
                        Barang yang Disewa
                    </h4>

                    <div class="space-y-2">
                        @foreach ($selectedItems as $selectedItem)
                            <div class="flex items-center justify-between rounded-md bg-gray-50 p-3">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $selectedItem['name'] }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        {{ $selectedItem['quantity'] }} unit ×
                                        Rp {{ number_format($selectedItem['price_per_day'], 0, ',', '.') }}/hari
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    wire:click="removeItem({{ $selectedItem['item_id'] }})"
                                    class="text-sm font-medium text-red-600 hover:text-red-500"
                                >
                                    Hapus
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">
                    Catatan
                </label>

                <textarea
                    id="notes"
                    wire:model="notes"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    placeholder="Catatan tambahan..."
                ></textarea>

                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; background-color: #4f46e5 !important; color: #ffffff !important;"
                class="rounded-md px-4 py-2 text-sm font-semibold"
            >
                Ajukan Penyewaan
            </button>
        </form>
    </div>
</div>

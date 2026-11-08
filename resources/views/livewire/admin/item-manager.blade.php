<div class="space-y-6">
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg bg-white p-6 shadow">
        <h3 class="mb-6 text-lg font-semibold text-gray-900">
            {{ $isEditing ? 'Edit Barang' : 'Tambah Barang' }}
        </h3>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">
                    Kategori
                </label>

                <select
                    id="category_id"
                    wire:model="category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >
                    <option value="">Pilih kategori</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Nama barang
                </label>

                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">
                    Slug
                </label>

                <input
                    id="slug"
                    type="text"
                    wire:model="slug"
                    placeholder="contoh: kamera-canon"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                >

                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    wire:model="description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                ></textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700">
                        Harga per hari
                    </label>

                    <input
                        id="price_per_day"
                        type="number"
                        wire:model="price_per_day"
                        min="0"
                        step="1000"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('price_per_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">
                        Stok
                    </label>

                    <input
                        id="stock"
                        type="number"
                        wire:model="stock"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="is_active"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                >

                <span class="text-sm text-gray-700">Barang aktif</span>
            </label>

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                </button>

                @if ($isEditing)
                    <button
                        type="button"
                        wire:click="resetForm"
                        class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300"
                    >
                        Batal
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">
            Daftar Barang
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Barang
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Kategori
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Harga/Hari
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">
                            Stok
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $item->slug }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $item->category->name }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                Rp {{ number_format($item->price_per_day, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $item->stock }}
                            </td>

                            <td class="space-x-2 px-4 py-3 text-right text-sm">
                                <button
                                    wire:click="edit({{ $item->id }})"
                                    class="font-medium text-indigo-600 hover:text-indigo-500"
                                >
                                    Edit
                                </button>

                                <button
                                    wire:click="delete({{ $item->id }})"
                                    wire:confirm="Hapus barang ini?"
                                    class="font-medium text-red-600 hover:text-red-500"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Kategori</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $categoryCount }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Jenis Barang</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $itemCount }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Stok Tersedia</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $availableStock }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Rental Pending</p>
        <p class="mt-2 text-3xl font-bold text-yellow-600">
            {{ $pendingRentals }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Rental Disetujui</p>
        <p class="mt-2 text-3xl font-bold text-green-600">
            {{ $approvedRentals }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm text-gray-500">Rental Selesai</p>
        <p class="mt-2 text-3xl font-bold text-blue-600">
            {{ $completedRentals }}
        </p>
    </div>

    <div class="rounded-lg bg-white p-6 shadow sm:col-span-2">
        <p class="text-sm text-gray-500">Total Pendapatan</p>
        <p class="mt-2 text-3xl font-bold text-indigo-600">
            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </p>
    </div>
</div>
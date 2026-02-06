<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-gauge-high text-indigo-600"></i>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLE --}}
    @push('styles')
    <style>
        .stat-card {
            transition: .25s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 22px rgba(0,0,0,0.08);
        }
    </style>
    @endpush

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- WELCOME CARD --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-2">
                    Selamat datang kembali, {{ auth()->user()->name }} 👋
                </h3>
                <p class="text-gray-600">
                    Kamu berhasil login. Silakan pilih menu di sidebar untuk mulai mengelola data.
                </p>
            </div>

            {{-- STATISTICS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 shadow rounded-lg stat-card border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg">
                            <i class="fa-solid fa-box text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Produk</p>
                            <h3 class="text-2xl font-bold">{{ $stats['products'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 shadow rounded-lg stat-card border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Pesanan</p>
                            <h3 class="text-2xl font-bold">{{ $stats['orders'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 shadow rounded-lg stat-card border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Pengguna</p>
                            <h3 class="text-2xl font-bold">{{ $stats['users'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

            </div>

            {{-- QUICK ACTIONS --}}
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fa-solid fa-box-open text-indigo-600 text-xl"></i>
                        <span class="font-medium">Kelola Produk</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fa-solid fa-receipt text-green-600 text-xl"></i>
                        <span class="font-medium">Kelola Pesanan</span>
                    </a>

                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chart-line text-yellow-600 text-xl"></i>
                        <span class="font-medium">Laporan & Statistik</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>

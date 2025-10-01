@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Peminjaman Buku</h1>
            <p class="text-lg text-gray-600">Kelola peminjaman buku perpustakaan dengan mudah</p>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Table Header -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Buku Tersedia</h2>
                    <div class="flex gap-4">
                        <!-- Tambahkan filter/search jika diperlukan -->
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            <th scope="col" class="hidden lg:table-cell px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bukus as $buku)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-20 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                        @if($buku->gambar)
                                            <img src="{{ asset('storage/' . $buku->gambar) }}" 
                                                alt="{{ $buku->judul }}" 
                                                class="h-full w-full object-cover"
                                                onerror="this.src='{{ asset('images/book-placeholder.png') }}'">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 max-w-[200px] truncate">{{ $buku->judul }}</div>
                                        <div class="text-sm text-gray-500">{{ $buku->pengarang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $buku->penerbit }}</div>
                                <div class="text-sm text-gray-500">{{ $buku->tahun_terbit }}</div>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $buku->genre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($buku->jumlah > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></div>
                                        {{ $buku->jumlah }} Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2"></div>
                                        Stok Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($buku->jumlah > 0)
                                    <form action="{{ route('peminjaman.ajukan', $buku->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            class="group relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M15 5v2a3 3 0 01-3 3h-2a3 3 0 01-3-3V5H5v11a2 2 0 002 2h10a2 2 0 002-2V5h-4z" />
                                                    <path d="M10 2a1 1 0 011 1v2h2V3a3 3 0 00-6 0v2h2V3a1 1 0 011-1z" />
                                                </svg>
                                            </span>
                                            <span class="pl-8">Pinjam Buku</span>
                                        </button>
                                    </form>
                                @else
                                    <button disabled 
                                        class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-500 bg-gray-50 cursor-not-allowed">
                                        <svg class="mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                        Tidak Tersedia
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Buku</h3>
                                    <p class="text-gray-500">Belum ada buku yang tersedia untuk dipinjam.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bukus->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $bukus->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

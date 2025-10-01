@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Peminjaman & Pengembalian</h1>
            <p class="mt-2 text-gray-600">Daftar semua aktivitas peminjaman dan pengembalian buku Anda</p>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Pengembalian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($riwayat as $p)
                        <tr class="hover:bg-gray-50 
                            @if($p->status == 'menunggu') border-l-4 border-blue-200
                            @elseif($p->status == 'disetujui' && now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian))) border-l-4 border-red-200
                            @elseif($p->status == 'disetujui') border-l-4 border-green-200
                            @elseif($p->status == 'menunggu_konfirmasi') border-l-4 border-yellow-200
                            @elseif($p->status == 'ditolak') border-l-4 border-red-200
                            @elseif($p->status == 'dikembalikan' || $p->status == 'selesai') border-l-4 border-green-200
                            @endif">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-16 w-12 flex-shrink-0">
                                        @if($p->buku->gambar)
                                            <img src="{{ asset('storage/' . $p->buku->gambar) }}" 
                                                alt="{{ $p->buku->judul }}" 
                                                class="h-16 w-12 object-cover rounded-sm shadow-sm">
                                        @else
                                            <div class="h-16 w-12 bg-gray-100 rounded-sm flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $p->buku->judul }}</div>
                                        <div class="text-sm text-gray-500">{{ $p->buku->pengarang }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" title="Tanggal dan waktu buku dipinjam">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->isoFormat('D MMMM YYYY, HH:mm') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" title="Batas waktu pengembalian buku">
                                <div class="text-sm @if(now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian)) && !in_array($p->status, ['dikembalikan', 'selesai'])) text-red-600 font-medium @else text-gray-900 @endif">
                                    {{ \Carbon\Carbon::parse($p->batas_pengembalian)->isoFormat('D MMMM YYYY, HH:mm') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->batas_pengembalian)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" title="Tanggal dan waktu pengembalian buku">
                                @if($p->tanggal_kembali && ($p->status == 'dikembalikan' || $p->status == 'selesai'))
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->isoFormat('D MMMM YYYY, HH:mm') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->diffForHumans() }}</div>
                                @elseif($p->status == 'menunggu_konfirmasi')
                                    <div class="text-sm text-yellow-600 font-medium">Pengajuan Pengembalian</div>
                                    <div class="text-xs text-gray-500">Diajukan {{ \Carbon\Carbon::parse($p->updated_at)->diffForHumans() }}</div>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status == 'menunggu')
                                    <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Menunggu Persetujuan
                                        <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                            Menunggu persetujuan admin
                                        </span>
                                    </span>
                                @elseif($p->status == 'disetujui')
                                    @if(now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian)))
                                        <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Terlambat
                                            <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                                Melewati batas waktu pengembalian
                                            </span>
                                        </span>
                                    @else
                                        <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Sedang Dipinjam
                                            <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                                Masih dalam masa peminjaman
                                            </span>
                                        </span>
                                    @endif
                                @elseif($p->status == 'menunggu_konfirmasi')
                                    <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Menunggu Konfirmasi
                                        <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                            Pengajuan pengembalian menunggu konfirmasi admin
                                        </span>
                                    </span>
                                @elseif($p->status == 'ditolak')
                                    <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Pengajuan Ditolak
                                        <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                            Pengajuan peminjaman ditolak oleh admin
                                        </span>
                                    </span>
                                @elseif($p->status == 'dikembalikan' || $p->status == 'selesai')
                                    <span class="group relative inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Selesai
                                        <span class="pointer-events-none absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-white opacity-0 transition before:absolute before:left-1/2 before:top-full before:-translate-x-1/2 before:border-4 before:border-transparent before:border-t-black before:content-[''] group-hover:opacity-100">
                                            Buku telah dikembalikan
                                        </span>
                                    </span>
                                @endif

                                @if($p->tanggal_kembali && ($p->status == 'dikembalikan' || $p->status == 'selesai'))
                                    <div class="mt-1 text-xs text-gray-500">
                                        Dikembalikan {{ \Carbon\Carbon::parse($p->tanggal_kembali)->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status == 'disetujui' && now()->gt(\Carbon\Carbon::parse($p->batas_pengembalian)))
                                    <span class="text-sm text-red-600 font-medium">
                                        {{ now()->diffInDays($p->batas_pengembalian) }} hari
                                    </span>
                                @elseif($p->tanggal_kembali && ($p->status == 'dikembalikan' || $p->status == 'selesai'))
                                    @if(\Carbon\Carbon::parse($p->tanggal_kembali)->gt(\Carbon\Carbon::parse($p->batas_pengembalian)))
                                        <span class="text-sm text-red-600 font-medium">
                                            {{ \Carbon\Carbon::parse($p->tanggal_kembali)->diffInDays($p->batas_pengembalian) }} hari
                                        </span>
                                    @else
                                        <span class="text-sm text-green-600 font-medium">Tepat Waktu</span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Riwayat</h3>
                                    <p class="text-gray-500">Anda belum pernah meminjam buku</p>
                                    <a href="{{ route('buku.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                        </svg>
                                        Lihat Katalog Buku
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

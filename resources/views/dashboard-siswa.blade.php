@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">                    Selamat datang, {{ Auth::user()->name }}!
                </h2>
                <p class="mt-3 text-lg text-blue-100 sm:mt-4">
                    Selamat datang di Perpustakaan Digital SMK 40 Jakarta. Akses ribuan koleksi buku dalam genggaman Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Links -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Katalog Buku -->
            <a href="{{ route('buku.index') }}" class="bg-white overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-medium text-gray-900">Katalog Buku</h2>
                            <p class="mt-1 text-sm text-gray-500">Telusuri koleksi buku</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Peminjaman Buku -->
            <a href="{{ route('peminjaman.index') }}" class="bg-white overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-medium text-gray-900">Peminjaman</h2>
                            <p class="mt-1 text-sm text-gray-500">Pinjam buku baru</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('peminjaman.riwayat') }}" class="bg-white overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-medium text-gray-900">Riwayat</h2>
                            <p class="mt-1 text-sm text-gray-500">Lihat riwayat peminjaman</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Pengembalian -->
            <a href="{{ route('peminjaman.pengembalian') }}" class="bg-white overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-medium text-gray-900">Pengembalian</h2>
                            <p class="mt-1 text-sm text-gray-500">Kembalikan buku</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tips Peminjaman Buku</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Durasi Peminjaman</h4>
                        <p class="mt-1 text-sm text-gray-500">Batas waktu peminjaman adalah 7 hari</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Keterlambatan</h4>
                        <p class="mt-1 text-sm text-gray-500">Hindari keterlambatan pengembalian buku</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Kondisi Buku</h4>
                        <p class="mt-1 text-sm text-gray-500">Jaga buku tetap dalam kondisi baik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

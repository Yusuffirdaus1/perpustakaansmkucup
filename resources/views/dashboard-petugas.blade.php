@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Petugas</h1>
            <p class="mt-2 text-gray-600">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <a href="{{ url('/peminjaman/daftar-pengembalian') }}" class="block bg-white rounded-xl shadow-lg p-6 hover:bg-indigo-50 transition">
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 text-indigo-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7v4a2 2 0 01-2 2H7a2 2 0 01-2-2V7"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2"/>
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-900">Konfirmasi Pengembalian</div>
                        <div class="text-sm text-gray-500">Lihat & konfirmasi pengembalian buku</div>
                    </div>
                </div>
            </a>
            <a href="{{ url('/peminjaman/pengajuan') }}" class="block bg-white rounded-xl shadow-lg p-6 hover:bg-indigo-50 transition">
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-900">Pengajuan Peminjaman</div>
                        <div class="text-sm text-gray-500">Kelola permintaan peminjaman buku</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
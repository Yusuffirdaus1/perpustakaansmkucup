<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Peminjaman Buku') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('peminjaman.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Pilih Buku</label>
                        <select name="buku_id" class="w-full border rounded p-2" required>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id }}">{{ $buku->judul }} ({{ $buku->jumlah }} tersedia)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="w-full border rounded p-2" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Batas Pengembalian</label>
                        <input type="date" name="batas_pengembalian" class="w-full border rounded p-2" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Pinjam</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

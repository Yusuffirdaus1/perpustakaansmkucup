@extends('layouts.app')

@section('content')
<!-- Add CSRF Token for JavaScript use -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header dan Filter -->
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-7 text-gray-900 sm:text-4xl sm:truncate">
                    Katalog Buku
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Temukan buku yang ingin Anda pinjam
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('buku.index') }}">
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Search -->
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul buku..." 
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-lg text-sm">
                </div>
                <!-- Genre Filter -->
                <div class="relative">
                    <select name="genre" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Genre</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Availability Filter -->
                <div class="relative">
                    <select name="availability" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Cari</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading State -->
    <div id="loading" class="hidden">
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Book Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($bukus as $buku)
        <div class="flex flex-col bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden group relative">
            <!-- Admin Actions -->
            @if(auth()->user() && auth()->user()->role === 'admin')
            <div class="absolute top-2 left-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex gap-2">
                <a href="{{ route('buku.edit', $buku->id) }}" 
                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white shadow-lg text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                
                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku {{ $buku->judul }}?')"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white shadow-lg text-gray-600 hover:text-red-600 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
              <!-- Book Cover -->
            <div class="relative aspect-[2/3] bg-gray-200 cursor-pointer" onclick="showBookDetails({{ json_encode($buku) }})">
                @if($buku->gambar)
                    <img src="{{ asset('storage/' . $buku->gambar) }}" 
                        alt="{{ $buku->judul }}" 
                        class="absolute inset-0 w-full h-full object-cover"
                        onerror="this.src='{{ asset('images/book-placeholder.png') }}'">
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <!-- Availability Badge -->
                @if($buku->jumlah > 0)
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $buku->jumlah }} Tersedia
                        </span>
                    </div>
                @else
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Stok Habis
                        </span>
                    </div>
                @endif
            </div>

            <!-- Book Info -->
            <div class="flex-1 p-4">
                <div class="flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-900 cursor-pointer hover:text-blue-600" 
                        onclick="showBookDetails({{ json_encode($buku) }})">
                        {{ $buku->judul }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $buku->pengarang }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</p>
                    
                    <!-- Genre Badge -->
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $buku->genre }}
                        </span>
                    </div>

                    @if(auth()->user() && auth()->user()->role === 'siswa')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @if($buku->jumlah > 0)
                            <form action="/peminjaman/ajukan/{{ $buku->id }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M15 5v2a3 3 0 01-3 3h-2a3 3 0 01-3-3V5H5v11a2 2 0 002 2h10a2 2 0 002-2V5h-4z" />
                                        <path d="M10 2a1 1 0 011 1v2h2V3a3 3 0 00-6 0v2h2V3a1 1 0 011-1z" />
                                    </svg>
                                    Pinjam Buku
                                </button>
                            </form>
                        @else
                            <button disabled 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-gray-500 bg-gray-100 cursor-not-allowed">
                                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Tidak Tersedia
                            </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada buku</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada buku yang tersedia dalam katalog.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bukus->hasPages())
    <div class="mt-6">
        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
            {{ $bukus->links() }}
        </nav>
    </div>
    @endif
</div>

<!-- Book Details Modal -->
<div id="bookModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-3xl w-full mx-4 overflow-hidden shadow-xl transform transition-all">
        <div class="absolute top-0 right-0 pt-4 pr-4">
            <button onclick="closeBookModal()" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-4 pt-5 pb-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Book Cover -->
                <div class="md:col-span-1">
                    <div class="aspect-[2/3] bg-gray-200 rounded-lg overflow-hidden">
                        <img id="modalImage" class="w-full h-full object-cover" alt="Book Cover">
                    </div>
                </div>

                <!-- Book Details -->
                <div class="md:col-span-2">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-4"></h3>
                    
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pengarang</dt>
                            <dd id="modalAuthor" class="mt-1 text-sm text-gray-900"></dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                            <dd id="modalPublisher" class="mt-1 text-sm text-gray-900"></dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tahun Terbit</dt>
                            <dd id="modalYear" class="mt-1 text-sm text-gray-900"></dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Genre</dt>
                            <dd id="modalGenre" class="mt-1"></dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd id="modalStatus" class="mt-1"></dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sinopsis</dt>
                            <dd id="modalSynopsis" class="mt-1 text-sm text-gray-900"></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        @if(auth()->user() && auth()->user()->role === 'siswa')
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <div id="modalAction" class="w-full sm:w-auto"></div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Search and Filter Functions
const searchInput = document.getElementById('search');
const genreFilter = document.getElementById('genre-filter');
const availabilityFilter = document.getElementById('availability-filter');
const loading = document.getElementById('loading');

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function filterBooks() {
    const searchQuery = searchInput.value.toLowerCase().trim();
    const genreQuery = genreFilter.value.toLowerCase().trim();
    const availabilityQuery = availabilityFilter.value;

    // Show loading
    if (loading) loading.classList.remove('hidden');

    const bookCards = document.querySelectorAll('.grid > div.flex.flex-col');
    let visibleCount = 0;

    bookCards.forEach(card => {
        // Get all searchable fields
        const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const author = card.querySelector('.text-gray-500')?.textContent.toLowerCase() || '';
        const genreBadge = card.querySelector('.bg-blue-100');
        const genre = genreBadge ? genreBadge.textContent.trim().toLowerCase() : '';
        const availabilityBadge = card.querySelector('.bg-green-100');
        const isAvailable = availabilityBadge !== null;

        // Match conditions
        const matchesSearch = !searchQuery || 
            title.includes(searchQuery) || 
            author.includes(searchQuery);

        const matchesGenre = !genreQuery || genre === genreQuery;

        const matchesAvailability = 
            availabilityQuery === '' || 
            (availabilityQuery === 'available' && isAvailable) || 
            (availabilityQuery === 'unavailable' && !isAvailable);

        // Show/hide card based on all conditions
        const shouldShow = matchesSearch && matchesGenre && matchesAvailability;
        card.classList.toggle('hidden', !shouldShow);

        if (shouldShow) visibleCount++;
    });

    // Show/hide "no results" message
    const noResultsDiv = document.querySelector('.col-span-full');
    if (noResultsDiv) {
        if (visibleCount === 0) {
            noResultsDiv.classList.remove('hidden');
            const title = noResultsDiv.querySelector('h3');
            const description = noResultsDiv.querySelector('p');
            if (title && description) {
                title.textContent = 'Tidak ada buku yang sesuai';
                description.textContent = `Tidak ditemukan buku dengan kriteria yang dipilih.
                    ${searchQuery ? ` Kata kunci: "${searchInput.value}"` : ''}
                    ${genreQuery ? ` Genre: "${genreFilter.value}"` : ''}
                    ${availabilityQuery ? ` Status: "${availabilityFilter.options[availabilityFilter.selectedIndex].text}"` : ''}`;
            }
        } else {
            noResultsDiv.classList.add('hidden');
        }
    }

    // Hide loading
    if (loading) loading.classList.add('hidden');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add search input event listeners
    searchInput.addEventListener('input', debounce(filterBooks, 300));
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBooks();
        }
    });

    // Add filter change event listeners
    genreFilter.addEventListener('change', filterBooks);
    availabilityFilter.addEventListener('change', filterBooks);

    // Initialize from URL parameters
    const params = new URLSearchParams(window.location.search);
    if (params.has('search')) {
        searchInput.value = params.get('search');
        filterBooks();
    }
    if (params.has('genre')) {
        genreFilter.value = params.get('genre');
        filterBooks();
    }
    if (params.has('availability')) {
        availabilityFilter.value = params.get('availability');
        filterBooks();
    }
});
</script>
@endpush
@endsection

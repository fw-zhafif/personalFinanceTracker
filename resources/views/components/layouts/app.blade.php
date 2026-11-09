<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Personal Finance Tracker' }}</title>

    {{-- CDN Tailwind CSS untuk tahap pengembangan cepat --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 font-sans">

    {{-- Navigation Bar --}}
    <nav class="bg-blue-600 text-white shadow-md mb-6">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('transactions.index') }}" class="text-xl font-bold tracking-wide">
                💰 FinanceTracker
            </a>

            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm bg-blue-700 px-3 py-1 rounded-full">
                        👤 {{ Auth::user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    {{-- Main Content Area --}}
    <main class="max-w-7xl mx-auto px-4 pb-12">
        {{ $slot }}
    </main>

</body>
</html>
<!DOCTYPE html>
<html lang="id" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TPAS Work Form')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#ff8563',
                            dark: '#ff6b4a',
                            light: '#fff5f2',
                        },
                        secondary: '#ffb59a',
                        accent: '#ff9775',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans bg-gray-50 min-h-screen">
    @auth
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo & Mobile Menu Button -->
                    <div class="flex items-center gap-4">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-primary">
                            TPAS Work Form
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center gap-6">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary font-medium transition">Dashboard</a>
                        <a href="{{ route('work-orders.create') }}" class="text-gray-700 hover:text-primary font-medium transition">Input Work Order</a>
                        <a href="{{ route('track.index') }}" class="text-gray-700 hover:text-primary font-medium transition">Track</a>
                        <a href="{{ route('reports.index') }}" class="text-gray-700 hover:text-primary font-medium transition">Reports</a>
                    </nav>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3" x-data="{ open: false }">
                        <div class="text-sm text-right hidden sm:block">
                            <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-gray-500 text-xs">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="relative">
                            <button @click="open = !open" class="p-2 rounded-full bg-primary-light text-primary">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden bg-white border-t">
                <nav class="px-4 py-3 space-y-2">
                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Dashboard</a>
                    <a href="{{ route('work-orders.create') }}" @click="mobileMenuOpen = false" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Input Work Order</a>
                    <a href="{{ route('track.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Track</a>
                    <a href="{{ route('reports.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Reports</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-red-600">Logout</button>
                    </form>
                </nav>
            </div>
        </header>
    @endauth

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @auth
            @yield('content')
        @else
            @yield('content')
        @endauth
    </main>

    <!-- Toast Notifications -->
    <script>
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Show success messages from session
        @if(session('success'))
            setTimeout(() => showToast('{{ session('success') }}', 'success'), 100);
        @endif
        @if(session('error'))
            setTimeout(() => showToast('{{ session('error') }}', 'error'), 100);
        @endif
    </script>

    @stack('scripts')
</body>
</html>

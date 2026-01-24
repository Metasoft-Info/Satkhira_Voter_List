<!DOCTYPE html>
<html lang="bn" x-data="{ sidebarOpen: true, lang: 'bn' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'অ্যাডমিন') - সাতক্ষীরা-২ আসন</title>
    <link rel="icon" type="image/png" href="/favicon.png?v=6">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s ease-in-out; }
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Hind Siliguri', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="sidebar-transition bg-gradient-to-b from-purple-900 via-purple-800 to-indigo-900 text-white"
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            <div class="p-4">
                <!-- Logo -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center" x-show="sidebarOpen">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-vote-yea text-purple-700 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h1 class="font-bold text-sm">সাতক্ষীরা-২</h1>
                            <p class="text-purple-300 text-xs">ভোটার তথ্য</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-purple-300 hover:text-white">
                        <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">ড্যাশবোর্ড</span>
                    </a>
                    
                    <a href="{{ route('admin.voters') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.voters') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-users w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">সকল ভোটার</span>
                    </a>
                    
                    <a href="{{ route('admin.upload') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.upload') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-cloud-upload-alt w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">ডেটা আপলোড</span>
                    </a>

                    <div class="border-t border-purple-700 my-4"></div>
                    <p x-show="sidebarOpen" class="px-4 text-xs text-purple-400 uppercase tracking-wider mb-2">সাইট সেটিংস</p>
                    
                    <a href="{{ route('admin.banners') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.banners') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-images w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">ব্যানার</span>
                    </a>
                    
                    <a href="{{ route('admin.breaking-news') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.breaking-news') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-bullhorn w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">ব্রেকিং নিউজ</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'text-purple-200 hover:bg-white/10' }}">
                        <i class="fas fa-user-cog w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">ইউজার ম্যানেজমেন্ট</span>
                    </a>

                    <div class="border-t border-purple-700 my-4"></div>
                    
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center px-4 py-3 rounded-lg text-purple-200 hover:bg-white/10 transition">
                        <i class="fas fa-external-link-alt w-6"></i>
                        <span x-show="sidebarOpen" class="ml-3">পাবলিক সাইট</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4" x-show="sidebarOpen">
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-xs text-purple-300">লগইন: {{ auth()->guard('admin')->user()->name ?? 'Admin' }}</p>
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-300 hover:text-red-200 text-sm flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> লগআউট
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'ড্যাশবোর্ড')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Language Toggle -->
                    <button @click="lang = lang === 'bn' ? 'en' : 'bn'" 
                            class="flex items-center space-x-2 px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-globe"></i>
                        <span x-text="lang === 'bn' ? 'English' : 'বাংলা'" class="text-sm"></span>
                    </button>
                    
                    <!-- Date -->
                    <div class="text-sm text-gray-500">
                        {{ now()->format('d M Y') }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

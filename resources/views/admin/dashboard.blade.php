<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ড্যাশবোর্ড - সাতক্ষীরা-২ আসন ভোটার তথ্য</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ lang: 'bn' }">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">
                        <span x-show="lang === 'bn'">সাতক্ষীরা-২ আসন ভোটার তথ্য - অ্যাডমিন</span>
                        <span x-show="lang === 'en'" x-cloak>Satkhira-2 Constituency Voter Info - Admin</span>
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:text-purple-800">
                        <span x-show="lang === 'bn'">ড্যাশবোর্ড</span>
                        <span x-show="lang === 'en'" x-cloak>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.voters') }}" class="text-gray-600 hover:text-purple-600">
                        <span x-show="lang === 'bn'">সকল ভোটার</span>
                        <span x-show="lang === 'en'" x-cloak>All Voters</span>
                    </a>
                    <a href="{{ route('admin.upload') }}" class="text-gray-600 hover:text-purple-600">
                        <span x-show="lang === 'bn'">আপলোড</span>
                        <span x-show="lang === 'en'" x-cloak>Upload</span>
                    </a>
                    <a href="{{ route('voters.index') }}" target="_blank" class="text-gray-600 hover:text-purple-600">
                        <span x-show="lang === 'bn'">পাবলিক সাইট</span>
                        <span x-show="lang === 'en'" x-cloak>Public Site</span>
                    </a>
                    <button @click="lang = lang === 'bn' ? 'en' : 'bn'" class="text-gray-600 hover:text-purple-600 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span x-text="lang === 'bn' ? 'English' : 'বাংলা'"></span>
                    </button>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <span x-show="lang === 'bn'">লগআউট</span>
                            <span x-show="lang === 'en'" x-cloak>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Voters -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">
                            <span x-show="lang === 'bn'">মোট ভোটার</span>
                            <span x-show="lang === 'en'" x-cloak>Total Voters</span>
                        </p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_voters']) }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Upazilas -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">
                            <span x-show="lang === 'bn'">উপজেলা</span>
                            <span x-show="lang === 'en'" x-cloak>Upazilas</span>
                        </p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['upazilas'] }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unions -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">
                            <span x-show="lang === 'bn'">ইউনিয়ন</span>
                            <span x-show="lang === 'en'" x-cloak>Unions</span>
                        </p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['unions'] }}</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Vote Centers -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">
                            <span x-show="lang === 'bn'">ভোট কেন্দ্র</span>
                            <span x-show="lang === 'en'" x-cloak>Vote Centers</span>
                        </p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['vote_centers'] }}</p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <span x-show="lang === 'bn'">দ্রুত কাজ</span>
                <span x-show="lang === 'en'" x-cloak>Quick Actions</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.voters') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition group">
                    <div class="bg-blue-100 rounded-lg p-3 group-hover:bg-blue-500 transition">
                        <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            <span x-show="lang === 'bn'">সকল ভোটার দেখুন</span>
                            <span x-show="lang === 'en'" x-cloak>View All Voters</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span x-show="lang === 'bn'">ভোটার তালিকা দেখুন</span>
                            <span x-show="lang === 'en'" x-cloak>Browse voter list</span>
                        </p>
                    </div>
                </a>

                <a href="{{ route('admin.upload') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-md transition group">
                    <div class="bg-purple-100 rounded-lg p-3 group-hover:bg-purple-500 transition">
                        <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            <span x-show="lang === 'bn'">Excel আপলোড</span>
                            <span x-show="lang === 'en'" x-cloak>Excel Upload</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span x-show="lang === 'bn'">ভোটার ডেটা আপডেট করুন</span>
                            <span x-show="lang === 'en'" x-cloak>Update voter data</span>
                        </p>
                    </div>
                </a>

                <form method="POST" action="{{ route('admin.reset.voters') }}" 
                      onsubmit="return confirm('আপনি কি নিশচিত সব ভোটার ডেটা মুছে ফেলতে চান? এটি পূর্বাবস্থায় ফেরানো যাবে না!');" 
                      class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-500 hover:shadow-md transition group cursor-pointer">
                    @csrf
                    <button type="submit" class="flex items-center w-full">
                        <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-500 transition">
                            <svg class="w-6 h-6 text-red-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div class="ml-4 text-left">
                            <p class="text-sm font-medium text-gray-600">
                                <span x-show="lang === 'bn'">সব ডেটা মুছুন</span>
                                <span x-show="lang === 'en'" x-cloak>Reset All Data</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <span x-show="lang === 'bn'">ভোটার ডেটা মুছে ফেলুন</span>
                                <span x-show="lang === 'en'" x-cloak>Delete all voters</span>
                            </p>
                        </div>
                    </button>
                </form>

                <a href="{{ route('voters.index') }}" target="_blank" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-md transition group">
                    <div class="bg-green-100 rounded-lg p-3 group-hover:bg-green-500 transition">
                        <svg class="w-6 h-6 text-green-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            <span x-show="lang === 'bn'">পাবলিক সাইট দেখুন</span>
                            <span x-show="lang === 'en'" x-cloak>View Public Site</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span x-show="lang === 'bn'">ভোটার সার্চ ইন্টারফেস</span>
                            <span x-show="lang === 'en'" x-cloak>Voter search interface</span>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'সাতক্ষীরা-২ ভোটার তালিকা') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#7c3aed">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ভোটার তালিকা">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/favicon.svg">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Bangla -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
        
        .animate-marquee:hover {
            animation-play-state: paused;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(147, 51, 234, 0.2); }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        /* Offline indicator */
        .offline-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Offline/Online Status Badge -->
    <div id="connection-status" class="offline-badge hidden">
        <div class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-wifi-slash mr-2"></i>
            <span>অফলাইন মোড</span>
        </div>
    </div>

    <!-- Data Sync Progress -->
    <div id="sync-progress" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="animate-spin w-12 h-12 border-4 border-purple-500 border-t-transparent rounded-full mx-auto mb-4"></div>
            <h3 class="font-semibold text-gray-800 mb-2">অফলাইন ডেটা সিঙ্ক হচ্ছে...</h3>
            <p id="sync-status" class="text-sm text-gray-600">0 / 0 ভোটার</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div id="sync-bar" class="bg-purple-600 h-2 rounded-full transition-all" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Breaking News Bar -->
    @if($breakingNews->count() > 0)
    <div class="bg-gradient-to-r from-red-600 to-pink-600 text-white py-2 overflow-hidden">
        <div class="flex items-center max-w-7xl mx-auto px-4">
            <span class="flex-shrink-0 bg-white text-red-600 px-3 py-1 rounded-full text-xs font-bold mr-4 animate-pulse flex items-center">
                <i class="fas fa-bolt mr-1"></i>
                ব্রেকিং
            </span>
            <div class="overflow-hidden flex-grow">
                <div class="whitespace-nowrap animate-marquee">
                    @foreach($breakingNews as $news)
                        <span class="mx-8 inline-flex items-center">
                            <i class="fas fa-circle text-[6px] mr-3 text-red-300"></i>
                            {{ $news->content_bn }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="bg-white text-purple-600 p-3 rounded-full mr-4 shadow-lg">
                        <i class="fas fa-vote-yea text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">সাতক্ষীরা-২ নির্বাচনী এলাকা</h1>
                        <p class="text-purple-200 text-sm">ভোটার তালিকা অনুসন্ধান সিস্টেম</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Banner Slider -->
    @if($banners->count() > 0)
    <div x-data="{ current: 0, banners: {{ $banners->count() }} }" x-init="setInterval(() => current = (current + 1) % banners, 5000)" class="relative">
        <div class="relative h-[250px] md:h-[350px] overflow-hidden">
            @foreach($banners as $index => $banner)
                <div x-show="current === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="absolute inset-0">
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" 
                             alt="{{ $banner->title_bn }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-purple-600 to-indigo-600"></div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex items-end">
                        <div class="p-6 md:p-12 text-white max-w-3xl">
                            @if($banner->title_bn)
                                <h2 class="text-2xl md:text-4xl font-bold mb-2">{{ $banner->title_bn }}</h2>
                            @endif
                            @if($banner->subtitle_bn)
                                <p class="text-lg text-gray-200">{{ $banner->subtitle_bn }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @foreach($banners as $index => $banner)
                <button @click="current = {{ $index }}" 
                        :class="current === {{ $index }} ? 'bg-white' : 'bg-white/50'" 
                        class="w-3 h-3 rounded-full transition"></button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Main Search Section -->
    <main class="max-w-5xl mx-auto px-4 py-8 {{ $banners->count() > 0 ? '-mt-16' : '' }} relative z-10">
        <div class="glass rounded-2xl shadow-2xl p-6 md:p-8" 
             x-data="searchApp()" 
             x-init="init()">
            
            <!-- Search Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-search text-purple-600 mr-2"></i>
                    ভোটার তথ্য অনুসন্ধান
                </h2>
                <p class="text-gray-600">ফিল্টার ব্যবহার করে অথবা সরাসরি খুঁজুন</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="space-y-6">
                
                <!-- Search Method Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-hand-pointer mr-2 text-purple-600"></i>
                        খোঁজার মাধ্যম নির্বাচন করুন
                    </label>
                    <select name="search_type" 
                            x-model="searchType"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none transition">
                        <option value="">-- নির্বাচন করুন --</option>
                        <option value="voter_id">ভোটার আইডি নম্বর</option>
                        <option value="name">নাম</option>
                        <option value="father_name">পিতার নাম</option>
                        <option value="mother_name">মাতার নাম</option>
                        <option value="serial_no">সিরিয়াল নম্বর</option>
                    </select>
                </div>

                <!-- Search Input (shows when search type selected) -->
                <div x-show="searchType" x-transition class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-keyboard mr-2 text-purple-600"></i>
                        <span x-text="getSearchLabel()"></span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="query" 
                               x-model="query"
                               :placeholder="getSearchPlaceholder()"
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition">
                    </div>
                </div>

                <!-- Optional Filters Section -->
                <div class="border-t pt-6">
                    <h3 class="text-gray-700 font-medium mb-4 flex items-center">
                        <i class="fas fa-filter mr-2 text-purple-600"></i>
                        ফিল্টার (ঐচ্ছিক)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Upazila -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">উপজেলা</label>
                            <select name="upazila" 
                                    x-model="selectedUpazila"
                                    @change="onUpazilaChange()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none">
                                <option value="">সকল উপজেলা</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila['name'] }}">{{ $upazila['name'] }} ({{ $upazila['count'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Union -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">ইউনিয়ন</label>
                            <select name="union" 
                                    x-model="selectedUnion"
                                    @change="onUnionChange()"
                                    :disabled="!selectedUpazila"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none disabled:bg-gray-100">
                                <option value="">সকল ইউনিয়ন</option>
                                <template x-for="union in unions" :key="union.name">
                                    <option :value="union.name" x-text="union.name + ' (' + union.count + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Ward -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">ওয়ার্ড</label>
                            <select name="ward" 
                                    x-model="selectedWard"
                                    @change="onWardChange()"
                                    :disabled="!selectedUnion"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none disabled:bg-gray-100">
                                <option value="">সকল ওয়ার্ড</option>
                                <template x-for="ward in wards" :key="ward.name">
                                    <option :value="ward.name" x-text="ward.name + ' (' + ward.count + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Area Code -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">এরিয়া কোড</label>
                            <select name="area_code" 
                                    x-model="selectedAreaCode"
                                    @change="onAreaCodeChange()"
                                    :disabled="!selectedWard"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none disabled:bg-gray-100">
                                <option value="">সকল এরিয়া কোড</option>
                                <template x-for="area in areaCodes" :key="area.area_code">
                                    <option :value="area.area_code" x-text="area.name + ' (' + area.count + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Center -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">কেন্দ্র</label>
                            <select name="center" 
                                    x-model="selectedCenter"
                                    @change="updateEstimate()"
                                    :disabled="!selectedAreaCode"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none disabled:bg-gray-100">
                                <option value="">সকল কেন্দ্র</option>
                                <template x-for="center in centers" :key="center.center_name">
                                    <option :value="center.center_name" x-text="center.name + ' (' + center.count + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">লিঙ্গ</label>
                            <select name="gender" 
                                    x-model="selectedGender"
                                    @change="updateEstimate()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-purple-500 focus:outline-none">
                                <option value="">সকল</option>
                                <option value="পুরুষ">পুরুষ</option>
                                <option value="মহিলা">মহিলা</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Estimated Results -->
                <div x-show="hasFilters()" x-transition class="bg-purple-50 rounded-xl p-4 text-center">
                    <p class="text-purple-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        আনুমানিক ফলাফল: <span class="font-bold" x-text="estimatedCount"></span> জন ভোটার
                    </p>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                        :disabled="!canSearch()"
                        :class="canSearch() ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 pulse-glow' : 'bg-gray-400 cursor-not-allowed'"
                        class="w-full text-white py-4 rounded-xl text-lg font-bold transition transform hover:scale-[1.02] active:scale-[0.98] shadow-lg flex items-center justify-center">
                    <i class="fas fa-search mr-3"></i>
                    অনুসন্ধান করুন
                </button>
            </form>
        </div>
    </main>

    <!-- Quick Stats -->
    <section class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl font-bold text-purple-600 mb-2">{{ number_format($totalVoters ?? 0) }}</div>
                <div class="text-gray-600 text-sm">মোট ভোটার</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ number_format($maleVoters ?? 0) }}</div>
                <div class="text-gray-600 text-sm">পুরুষ ভোটার</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl font-bold text-pink-600 mb-2">{{ number_format($femaleVoters ?? 0) }}</div>
                <div class="text-gray-600 text-sm">মহিলা ভোটার</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl font-bold text-green-600 mb-2">{{ $centerCount ?? 0 }}</div>
                <div class="text-gray-600 text-sm">ভোট কেন্দ্র</div>
            </div>
        </div>
    </section>

    <!-- How to Use -->
    <section class="max-w-6xl mx-auto px-4 py-8">
        <h3 class="text-2xl font-bold text-gray-800 text-center mb-8">
            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
            কিভাবে ব্যবহার করবেন
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600">১</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">মাধ্যম বাছাই করুন</h4>
                <p class="text-gray-600 text-sm">ভোটার আইডি, নাম, পিতার নাম দিয়ে খুঁজুন অথবা শুধু ফিল্টার ব্যবহার করুন</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600">২</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">ফিল্টার করুন</h4>
                <p class="text-gray-600 text-sm">উপজেলা, ইউনিয়ন, ওয়ার্ড, কেন্দ্র দিয়ে ফিল্টার করুন - সব ঐচ্ছিক</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600">৩</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">ফলাফল দেখুন</h4>
                <p class="text-gray-600 text-sm">ভোটারের সম্পূর্ণ তথ্য কার্ড আকারে দেখুন</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-vote-yea mr-2 text-purple-400"></i>
                        সাতক্ষীরা-২ ভোটার তালিকা
                    </h4>
                    <p class="text-gray-400 text-sm">সাতক্ষীরা-২ নির্বাচনী এলাকার ভোটার তথ্য অনুসন্ধান সিস্টেম</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">দ্রুত লিঙ্ক</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-purple-400 transition"><i class="fas fa-home mr-2"></i>হোম</a></li>
                        <li><a href="{{ route('search') }}" class="hover:text-purple-400 transition"><i class="fas fa-search mr-2"></i>অনুসন্ধান</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">যোগাযোগ</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>সাতক্ষীরা, খুলনা বিভাগ</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>© {{ date('Y') }} সাতক্ষীরা-২ নির্বাচনী এলাকা। সর্বস্বত্ব সংরক্ষিত।</p>
                <p class="mt-2 text-gray-500">Developed by <span class="text-purple-400 font-medium">Mir Javed Jeetu</span></p>
            </div>
        </div>
    </footer>

    <script>
        function searchApp() {
            return {
                searchType: '',
                query: '',
                selectedUpazila: '',
                selectedUnion: '',
                selectedWard: '',
                selectedAreaCode: '',
                selectedCenter: '',
                selectedGender: '',
                unions: [],
                wards: [],
                areaCodes: [],
                centers: [],
                estimatedCount: 0,

                init() {
                    // Initial data
                },

                getSearchLabel() {
                    const labels = {
                        'voter_id': 'ভোটার আইডি নম্বর লিখুন',
                        'name': 'নাম লিখুন',
                        'father_name': 'পিতার নাম লিখুন',
                        'mother_name': 'মাতার নাম লিখুন',
                        'serial_no': 'সিরিয়াল নম্বর লিখুন'
                    };
                    return labels[this.searchType] || 'অনুসন্ধান করুন';
                },

                getSearchPlaceholder() {
                    const placeholders = {
                        'voter_id': 'যেমন: 1234567890123',
                        'name': 'যেমন: মোঃ করিম উদ্দিন',
                        'father_name': 'যেমন: মোঃ আব্দুল করিম',
                        'mother_name': 'যেমন: মোসাঃ ফাতেমা খাতুন',
                        'serial_no': 'যেমন: 001'
                    };
                    return placeholders[this.searchType] || 'এখানে লিখুন...';
                },

                hasFilters() {
                    return this.selectedUpazila || this.selectedUnion || this.selectedWard || 
                           this.selectedAreaCode || this.selectedCenter || this.selectedGender;
                },

                canSearch() {
                    return (this.searchType && this.query) || this.hasFilters();
                },

                async onUpazilaChange() {
                    this.selectedUnion = '';
                    this.selectedWard = '';
                    this.selectedAreaCode = '';
                    this.selectedCenter = '';
                    this.unions = [];
                    this.wards = [];
                    this.areaCodes = [];
                    this.centers = [];

                    if (this.selectedUpazila) {
                        const response = await fetch(`/api/filter/unions?upazila=${encodeURIComponent(this.selectedUpazila)}`);
                        this.unions = await response.json();
                        this.updateEstimate();
                    }
                },

                async onUnionChange() {
                    this.selectedWard = '';
                    this.selectedAreaCode = '';
                    this.selectedCenter = '';
                    this.wards = [];
                    this.areaCodes = [];
                    this.centers = [];

                    if (this.selectedUnion) {
                        const response = await fetch(`/api/filter/wards?upazila=${encodeURIComponent(this.selectedUpazila)}&union=${encodeURIComponent(this.selectedUnion)}`);
                        this.wards = await response.json();
                        this.updateEstimate();
                    }
                },

                async onWardChange() {
                    this.selectedAreaCode = '';
                    this.selectedCenter = '';
                    this.areaCodes = [];
                    this.centers = [];

                    if (this.selectedWard) {
                        const response = await fetch(`/api/filter/area-codes?upazila=${encodeURIComponent(this.selectedUpazila)}&union=${encodeURIComponent(this.selectedUnion)}&ward=${encodeURIComponent(this.selectedWard)}`);
                        this.areaCodes = await response.json();
                        this.updateEstimate();
                    }
                },

                async onAreaCodeChange() {
                    this.selectedCenter = '';
                    this.centers = [];

                    if (this.selectedAreaCode) {
                        const response = await fetch(`/api/filter/centers?upazila=${encodeURIComponent(this.selectedUpazila)}&union=${encodeURIComponent(this.selectedUnion)}&ward=${encodeURIComponent(this.selectedWard)}&area_code=${encodeURIComponent(this.selectedAreaCode)}`);
                        this.centers = await response.json();
                        this.updateEstimate();
                    }
                },

                async updateEstimate() {
                    let params = new URLSearchParams();
                    if (this.selectedUpazila) params.append('upazila', this.selectedUpazila);
                    if (this.selectedUnion) params.append('union', this.selectedUnion);
                    if (this.selectedWard) params.append('ward', this.selectedWard);
                    if (this.selectedAreaCode) params.append('area_code', this.selectedAreaCode);
                    if (this.selectedCenter) params.append('center', this.selectedCenter);
                    if (this.selectedGender) params.append('gender', this.selectedGender);

                    const response = await fetch(`/api/filter/count?${params.toString()}`);
                    const data = await response.json();
                    this.estimatedCount = data.count;
                }
            };
        }

        // ============================================
        // PWA - Service Worker & Offline Support
        // ============================================
        
        const DB_NAME = 'VoterListDB';
        const DB_VERSION = 1;
        const STORE_NAME = 'voters';
        const META_STORE = 'meta';
        
        // Open IndexedDB
        function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open(DB_NAME, DB_VERSION);
                
                request.onerror = () => reject(request.error);
                request.onsuccess = () => resolve(request.result);
                
                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    
                    // Create voters store with indexes
                    if (!db.objectStoreNames.contains(STORE_NAME)) {
                        const store = db.createObjectStore(STORE_NAME, { keyPath: 'id' });
                        store.createIndex('voter_id', 'voter_id', { unique: true });
                        store.createIndex('name', 'name', { unique: false });
                        store.createIndex('father_name', 'father_name', { unique: false });
                        store.createIndex('mother_name', 'mother_name', { unique: false });
                        store.createIndex('upazila', 'upazila', { unique: false });
                        store.createIndex('union', 'union', { unique: false });
                        store.createIndex('serial_no', 'serial_no', { unique: false });
                    }
                    
                    // Create meta store for sync info
                    if (!db.objectStoreNames.contains(META_STORE)) {
                        db.createObjectStore(META_STORE, { keyPath: 'key' });
                    }
                };
            });
        }
        
        // Store voters in IndexedDB
        async function storeVoters(voters) {
            const db = await openDatabase();
            return new Promise((resolve, reject) => {
                const transaction = db.transaction([STORE_NAME], 'readwrite');
                const store = transaction.objectStore(STORE_NAME);
                
                voters.forEach(voter => store.put(voter));
                
                transaction.oncomplete = () => resolve();
                transaction.onerror = () => reject(transaction.error);
            });
        }
        
        // Store meta info
        async function storeMeta(key, value) {
            const db = await openDatabase();
            return new Promise((resolve, reject) => {
                const transaction = db.transaction([META_STORE], 'readwrite');
                const store = transaction.objectStore(META_STORE);
                store.put({ key, value });
                transaction.oncomplete = () => resolve();
                transaction.onerror = () => reject(transaction.error);
            });
        }
        
        // Get meta info
        async function getMeta(key) {
            const db = await openDatabase();
            return new Promise((resolve, reject) => {
                const transaction = db.transaction([META_STORE], 'readonly');
                const store = transaction.objectStore(META_STORE);
                const request = store.get(key);
                request.onsuccess = () => resolve(request.result?.value);
                request.onerror = () => reject(request.error);
            });
        }
        
        // Get count from IndexedDB
        async function getLocalVoterCount() {
            const db = await openDatabase();
            return new Promise((resolve, reject) => {
                const transaction = db.transaction([STORE_NAME], 'readonly');
                const store = transaction.objectStore(STORE_NAME);
                const request = store.count();
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        }
        
        // Sync voters from server
        async function syncVoters() {
            try {
                // Check if sync is needed
                const syncResponse = await fetch('/api/voters/sync');
                const syncData = await syncResponse.json();
                
                const localChecksum = await getMeta('checksum');
                const localCount = await getLocalVoterCount();
                
                // If checksums match and counts match, no sync needed
                if (localChecksum === syncData.checksum && localCount === syncData.total) {
                    console.log('✅ অফলাইন ডেটা আপ-টু-ডেট আছে');
                    return;
                }
                
                console.log('🔄 অফলাইন ডেটা সিঙ্ক শুরু হচ্ছে...');
                
                // Show sync progress
                const progressDiv = document.getElementById('sync-progress');
                const statusText = document.getElementById('sync-status');
                const progressBar = document.getElementById('sync-bar');
                progressDiv.classList.remove('hidden');
                
                let page = 1;
                let totalFetched = 0;
                const totalVoters = syncData.total;
                
                while (true) {
                    const response = await fetch(`/api/voters/all?page=${page}`);
                    const data = await response.json();
                    
                    if (data.data.length === 0) break;
                    
                    await storeVoters(data.data);
                    totalFetched += data.data.length;
                    
                    // Update progress
                    const percent = Math.round((totalFetched / totalVoters) * 100);
                    statusText.textContent = `${totalFetched.toLocaleString('bn-BD')} / ${totalVoters.toLocaleString('bn-BD')} ভোটার`;
                    progressBar.style.width = `${percent}%`;
                    
                    if (!data.has_more) break;
                    page++;
                }
                
                // Store sync meta
                await storeMeta('checksum', syncData.checksum);
                await storeMeta('last_sync', new Date().toISOString());
                
                // Hide progress
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                }, 1000);
                
                console.log(`✅ ${totalFetched} ভোটার সফলভাবে সিঙ্ক হয়েছে`);
                
            } catch (error) {
                console.error('সিঙ্ক ব্যর্থ:', error);
                document.getElementById('sync-progress').classList.add('hidden');
            }
        }
        
        // Update connection status UI
        function updateConnectionStatus() {
            const statusBadge = document.getElementById('connection-status');
            if (!navigator.onLine) {
                statusBadge.classList.remove('hidden');
            } else {
                statusBadge.classList.add('hidden');
            }
        }
        
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('✅ Service Worker নিবন্ধিত:', registration.scope);
                })
                .catch(error => {
                    console.error('❌ Service Worker নিবন্ধন ব্যর্থ:', error);
                });
        }
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            updateConnectionStatus();
            console.log('🌐 অনলাইন হয়েছেন');
            // Sync when coming back online
            syncVoters();
        });
        
        window.addEventListener('offline', () => {
            updateConnectionStatus();
            console.log('📴 অফলাইন হয়েছেন');
        });
        
        // Initial sync on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateConnectionStatus();
            
            // Start syncing after a short delay
            setTimeout(() => {
                if (navigator.onLine) {
                    syncVoters();
                }
            }, 2000);
        });
    </script>
</body>
</html>

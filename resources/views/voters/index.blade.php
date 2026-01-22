<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>সাতক্ষীরা-২ আসন ভোটার তথ্য</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        [x-cloak] { display: none !important; }
        
        /* Dropdown visibility fix */
        select {
            color: #000000 !important;
            background-color: #ffffff !important;
        }
        select option {
            color: #000000 !important;
            background-color: #ffffff !important;
            padding: 10px !important;
        }
        
        /* Premium animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        
        /* Mobile scroll */
        @media (max-width: 640px) {
            .mobile-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        }
        
        /* Premium shadows */
        .card-shadow { box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .input-focus:focus { 
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
            border-color: #8b5cf6;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="voterSearch()">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left w-full sm:w-auto">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1 sm:mb-2">
                        <span x-show="lang === 'bn'">সাতক্ষীরা-২ আসন ভোটার তথ্য</span>
                        <span x-show="lang === 'en'" x-cloak>Satkhira-2 Constituency Voter Information</span>
                    </h1>
                    <p class="text-purple-100 text-sm sm:text-base">
                        <span x-show="lang === 'bn'">সাতক্ষীরা সদর ও দেবহাটা উপজেলা</span>
                        <span x-show="lang === 'en'" x-cloak>Satkhira Sadar & Debhata Upazila</span>
                    </p>
                </div>
                <button @click="toggleLang()" 
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-xl transition-all transform hover:scale-105 flex items-center space-x-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span class="font-semibold" x-text="lang === 'bn' ? 'English' : 'বাংলা'"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
        <!-- Search Form -->
        <div class="bg-white rounded-2xl card-shadow p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 animate-fade-in">
            <form @submit.prevent="searchVoters()">
                <div class="space-y-4 sm:space-y-6">
                    <!-- Search By Dropdown -->
                    <div>
                        <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="inline w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span x-show="lang === 'bn'">খোঁজার মাধ্যম নির্বাচন করুন</span>
                            <span x-show="lang === 'en'" x-cloak>Select Search Type</span>
                        </label>
                        <select x-model="searchBy" @change="resetSearchValue()" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl input-focus transition" required>
                            <option value="" x-text="lang === 'bn' ? '-- নির্বাচন করুন --' : '-- Select --'"></option>
                            @foreach($searchTypes as $type)
                                <option value="{{ $type->id }}" x-text="lang === 'bn' ? '{{ $type->name_bn }}' : '{{ $type->name_en }}'"></option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Search Input -->
                    <div x-show="searchBy" x-cloak>
                        <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2" x-text="getSearchLabel()"></label>
                        <input type="text" x-model="searchValue" :placeholder="getSearchPlaceholder()"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl input-focus transition" required>
                    </div>

                    <!-- Filter Section -->
                    <div class="border-t-2 border-gray-100 pt-4 sm:pt-6 mt-4 sm:mt-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span x-show="lang === 'bn'">ফিল্টার (ঐচ্ছিক)</span>
                            <span x-show="lang === 'en'" x-cloak>Filters (Optional)</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <!-- Upazila -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span x-show="lang === 'bn'">উপজেলা</span>
                                    <span x-show="lang === 'en'" x-cloak>Upazila</span>
                                </label>
                                <select x-model="upazila" @change="loadUnions()" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus transition">
                                    <option value="" x-text="lang === 'bn' ? 'সকল উপজেলা' : 'All Upazilas'"></option>
                                    @foreach($upazilas as $upazila)
                                        <option value="{{ $upazila }}">{{ $upazila }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Union -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span x-show="lang === 'bn'">ইউনিয়ন</span>
                                    <span x-show="lang === 'en'" x-cloak>Union</span>
                                </label>
                                <select x-model="union" @change="loadWards()" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus transition" :disabled="!upazila">
                                    <option value="" x-text="lang === 'bn' ? 'সকল ইউনিয়ন' : 'All Unions'"></option>
                                    <template x-for="u in unions" :key="u">
                                        <option :value="u" x-text="u"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Ward -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span x-show="lang === 'bn'">ওয়ার্ড</span>
                                    <span x-show="lang === 'en'" x-cloak>Ward</span>
                                </label>
                                <select x-model="ward" @change="loadAreaCodes()" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus transition" :disabled="!union">
                                    <option value="" x-text="lang === 'bn' ? 'সকল ওয়ার্ড' : 'All Wards'"></option>
                                    <template x-for="w in wards" :key="w">
                                        <option :value="w" x-text="w"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Area Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span x-show="lang === 'bn'">এরিয়া কোড</span>
                                    <span x-show="lang === 'en'" x-cloak>Area Code</span>
                                </label>
                                <select x-model="areaCode" @change="loadCenters()" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus transition" :disabled="!ward">
                                    <option value="" x-text="lang === 'bn' ? 'সকল এরিয়া কোড' : 'All Area Codes'"></option>
                                    <template x-for="ac in areaCodes" :key="ac.area_code">
                                        <option :value="ac.area_code" x-text="ac.area_code + ' - ' + ac.area_name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Center -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span x-show="lang === 'bn'">কেন্দ্র</span>
                                    <span x-show="lang === 'en'" x-cloak>Center</span>
                                </label>
                                <select x-model="center" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus transition" :disabled="!areaCode">
                                    <option value="" x-text="lang === 'bn' ? 'সকল কেন্দ্র' : 'All Centers'"></option>
                                    <template x-for="c in centers" :key="c.center_name">
                                        <option :value="c.center_name" x-text="c.center_no + ' - ' + c.center_name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="flex justify-center pt-4 sm:pt-6">
                        <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 gradient-bg text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center text-base sm:text-lg font-bold">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span x-show="lang === 'bn'">খুঁজুন</span>
                            <span x-show="lang === 'en'" x-cloak>Search</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div x-show="loading" x-cloak class="text-center py-12 animate-fade-in">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            <p class="text-gray-600 mt-4 text-sm sm:text-base">
                <span x-show="lang === 'bn'">অনুসন্ধান চলছে...</span>
                <span x-show="lang === 'en'" x-cloak>Searching...</span>
            </p>
        </div>

        <!-- Results - Premium Card View -->
        <div x-show="results.length > 0 && !loading" x-cloak class="animate-fade-in">
            <div class="bg-white rounded-2xl card-shadow p-4 sm:p-6 mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="lang === 'bn'">অনুসন্ধান ফলাফল (<span x-text="results.length"></span>টি রেকর্ড)</span>
                    <span x-show="lang === 'en'" x-cloak>Search Results (<span x-text="results.length"></span> Records)</span>
                </h2>
            </div>

            <!-- Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                <template x-for="voter in results" :key="voter.id">
                    <div class="bg-white rounded-xl card-shadow hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="gradient-bg text-white p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs sm:text-sm font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                    <span x-show="lang === 'bn'">সিরিয়াল</span>
                                    <span x-show="lang === 'en'" x-cloak>Serial</span>: 
                                    <span x-text="voter.serial_no"></span>
                                </span>
                                <span class="text-xs sm:text-sm font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full" x-text="voter.gender"></span>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold" x-text="voter.name"></h3>
                            <p class="text-purple-100 text-sm mt-1">
                                <span x-show="lang === 'bn'">ভোটার নং</span>
                                <span x-show="lang === 'en'" x-cloak>Voter ID</span>: 
                                <span class="font-mono" x-text="voter.voter_id"></span>
                            </p>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 sm:p-5 space-y-3">
                            <!-- Father's Name -->
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">
                                        <span x-show="lang === 'bn'">পিতার নাম</span>
                                        <span x-show="lang === 'en'" x-cloak>Father's Name</span>
                                    </p>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium" x-text="voter.father_name"></p>
                                </div>
                            </div>

                            <!-- Mother's Name -->
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">
                                        <span x-show="lang === 'bn'">মাতার নাম</span>
                                        <span x-show="lang === 'en'" x-cloak>Mother's Name</span>
                                    </p>
                                    <p class="text-sm sm:text-base text-gray-900 font-medium" x-text="voter.mother_name"></p>
                                </div>
                            </div>

                            <!-- Date of Birth & Occupation -->
                            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500 font-medium mb-1">
                                        <span x-show="lang === 'bn'">জন্ম তারিখ</span>
                                        <span x-show="lang === 'en'" x-cloak>Date of Birth</span>
                                    </p>
                                    <p class="text-sm text-gray-900 font-semibold" x-text="voter.date_of_birth || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium mb-1">
                                        <span x-show="lang === 'bn'">পেশা</span>
                                        <span x-show="lang === 'en'" x-cloak>Occupation</span>
                                    </p>
                                    <p class="text-sm text-gray-900 font-semibold" x-text="voter.occupation || 'N/A'"></p>
                                </div>
                            </div>

                            <!-- Location Info -->
                            <div class="bg-purple-50 rounded-lg p-3 space-y-2">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        <span class="font-semibold" x-text="voter.union"></span>, 
                                        <span x-show="lang === 'bn'">ওয়ার্ড</span>
                                        <span x-show="lang === 'en'" x-cloak>Ward</span>: 
                                        <span x-text="voter.ward"></span>
                                    </span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        <span x-show="lang === 'bn'">এলাকা কোড</span>
                                        <span x-show="lang === 'en'" x-cloak>Area Code</span>: 
                                        <span class="font-semibold" x-text="voter.area_code"></span> - 
                                        <span x-text="voter.area_name"></span>
                                    </span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        <span x-show="lang === 'bn'">কেন্দ্র</span>
                                        <span x-show="lang === 'en'" x-cloak>Center</span>: 
                                        <span class="font-semibold" x-text="voter.center_no"></span> - 
                                        <span x-text="voter.center_name"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- No Results -->
        <div x-show="searched && results.length === 0 && !loading" x-cloak class="bg-white rounded-2xl card-shadow p-8 sm:p-12 text-center animate-fade-in">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">
                <span x-show="lang === 'bn'">কোনো ফলাফল পাওয়া যায়নি</span>
                <span x-show="lang === 'en'" x-cloak>No Results Found</span>
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
                <span x-show="lang === 'bn'">অনুগ্রহ করে আপনার অনুসন্ধান শর্ত পরিবর্তন করে আবার চেষ্টা করুন</span>
                <span x-show="lang === 'en'" x-cloak>Please modify your search criteria and try again</span>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-6 sm:py-8 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm sm:text-base">
                <span x-show="lang === 'bn'">&copy; 2024 সাতক্ষীরা-২ আসন ভোটার তথ্য। সর্বস্বত্ব সংরক্ষিত।</span>
                <span x-show="lang === 'en'" x-cloak>&copy; 2024 Satkhira-2 Constituency Voter Information. All rights reserved.</span>
            </p>
            <p class="text-purple-100 text-xs sm:text-sm mt-2">
                <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span x-show="lang === 'bn'">অ্যাডমিন লগইন</span>
                    <span x-show="lang === 'en'" x-cloak>Admin Login</span>
                </a>
            </p>
        </div>
    </footer>

    <script>
        function voterSearch() {
            return {
                lang: 'bn',
                searchBy: '',
                searchValue: '',
                upazila: '',
                union: '',
                ward: '',
                areaCode: '',
                center: '',
                unions: [],
                wards: [],
                areaCodes: [],
                centers: [],
                results: [],
                loading: false,
                searched: false,

                searchTypes: @json($searchTypes),

                toggleLang() {
                    this.lang = this.lang === 'bn' ? 'en' : 'bn';
                },

                resetSearchValue() {
                    this.searchValue = '';
                },

                getSearchLabel() {
                    const type = this.searchTypes.find(t => t.id == this.searchBy);
                    if (!type) return '';
                    const label = this.lang === 'bn' ? type.name_bn : type.name_en;
                    return (this.lang === 'bn' ? label + ' লিখুন' : 'Enter ' + label);
                },

                getSearchPlaceholder() {
                    const type = this.searchTypes.find(t => t.id == this.searchBy);
                    if (!type) return '';
                    const label = this.lang === 'bn' ? type.name_bn : type.name_en;
                    return (this.lang === 'bn' ? label + ' লিখুন...' : 'Enter ' + label + '...');
                },

                async loadUnions() {
                    this.union = '';
                    this.ward = '';
                    this.areaCode = '';
                    this.center = '';
                    this.unions = [];
                    this.wards = [];
                    this.areaCodes = [];
                    this.centers = [];

                    if (!this.upazila) return;

                    try {
                        const response = await fetch(`/api/unions/${encodeURIComponent(this.upazila)}`);
                        this.unions = await response.json();
                    } catch (error) {
                        console.error('Error loading unions:', error);
                    }
                },

                async loadWards() {
                    this.ward = '';
                    this.areaCode = '';
                    this.center = '';
                    this.wards = [];
                    this.areaCodes = [];
                    this.centers = [];

                    if (!this.upazila || !this.union) return;

                    try {
                        const response = await fetch(`/api/wards/${encodeURIComponent(this.upazila)}/${encodeURIComponent(this.union)}`);
                        this.wards = await response.json();
                    } catch (error) {
                        console.error('Error loading wards:', error);
                    }
                },

                async loadAreaCodes() {
                    this.areaCode = '';
                    this.center = '';
                    this.areaCodes = [];
                    this.centers = [];

                    if (!this.upazila || !this.union || !this.ward) return;

                    try {
                        const response = await fetch(`/api/area-codes/${encodeURIComponent(this.upazila)}/${encodeURIComponent(this.union)}/${encodeURIComponent(this.ward)}`);
                        this.areaCodes = await response.json();
                    } catch (error) {
                        console.error('Error loading area codes:', error);
                    }
                },

                async loadCenters() {
                    this.center = '';
                    this.centers = [];

                    if (!this.upazila || !this.union || !this.ward || !this.areaCode) return;

                    try {
                        const response = await fetch(`/api/centers/${encodeURIComponent(this.upazila)}/${encodeURIComponent(this.union)}/${encodeURIComponent(this.ward)}/${encodeURIComponent(this.areaCode)}`);
                        this.centers = await response.json();
                    } catch (error) {
                        console.error('Error loading centers:', error);
                    }
                },

                async searchVoters() {
                    if (!this.searchBy || !this.searchValue) return;

                    this.loading = true;
                    this.searched = true;

                    const params = new URLSearchParams({
                        search_type: this.searchBy,
                        search_value: this.searchValue,
                    });

                    if (this.upazila) params.append('upazila', this.upazila);
                    if (this.union) params.append('union', this.union);
                    if (this.ward) params.append('ward', this.ward);
                    if (this.areaCode) params.append('area_code', this.areaCode);
                    if (this.center) params.append('center', this.center);

                    try {
                        const response = await fetch(`/voters/search?${params}`);
                        const data = await response.json();
                        this.results = data;
                    } catch (error) {
                        console.error('Search error:', error);
                        const errorMsg = this.lang === 'bn' 
                            ? 'অনুসন্ধানে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'
                            : 'Search error. Please try again.';
                        alert(errorMsg);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>

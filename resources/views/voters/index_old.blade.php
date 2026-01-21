<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সাতক্ষীরা সদর ভোটার তালিকা খোঁজ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        [x-cloak] { display: none !important; }
        
        /* Fix dropdown option visibility - force dark text */
        select {
            color: #000000 !important;
            background-color: #ffffff !important;
        }
        select option {
            color: #000000 !important;
            background-color: #ffffff !important;
            padding: 10px !important;
            font-size: 14px !important;
        }
        select option:checked {
            background-color: #e5e7eb !important;
            color: #000000 !important;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="voterSearch()">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div class="text-center flex-1">
                    <h1 class="text-4xl font-bold mb-2">
                        <span x-show="lang === 'bn'">সাতক্ষীরা সদর ভোটার তালিকা</span>
                        <span x-show="lang === 'en'" x-cloak>Satkhira Sadar Voter List</span>
                    </h1>
                    <p class="text-purple-100 text-lg">
                        <span x-show="lang === 'bn'">আপনার ভোটার তথ্য দ্রুত খুঁজে পান</span>
                        <span x-show="lang === 'en'" x-cloak>Find your voter information quickly</span>
                    </p>
                </div>
                <button @click="toggleLang()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span x-show="lang === 'bn'">English</span>
                    <span x-show="lang === 'en'" x-cloak>বাংলা</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Search Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Card -->
        <div class="bg-white rounded-2xl card-shadow p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 animate-fade-in">
            <form @submit.prevent="searchVoters()" class="space-y-4 sm:space-y-6">
                <!-- Search By Dropdown -->
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="inline w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span x-show="lang === 'bn'">খোঁজার মাধ্যম নির্বাচন করুন</span>
                        <span x-show="lang === 'en'" x-cloak>Select Search Type</span>
                    </label>
                    <select x-model="searchBy" @change="resetSearchValue()" style="color: #000 !important; background-color: #fff !important;" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition" required>
                        <option value="" style="color: #000 !important;" x-text="lang === 'bn' ? '-- নির্বাচন করুন --' : '-- Select --'"></option>
                        @foreach($searchTypes as $type)
                            <option value="{{ $type->id }}" style="color: #000 !important;" x-text="lang === 'bn' ? '{{ $type->name_bn }}' : '{{ $type->name_en }}'"></option>
                        @endforeach
                    </select>
                </div>

                <!-- Dynamic Search Input -->
                <div x-show="searchBy" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-2" x-text="getSearchLabel()"></label>
                    <input 
                        type="text" 
                        x-model="searchValue"
                        :placeholder="getSearchPlaceholder()"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                        required
                    >
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
                            <select x-model="upazila" @change="loadUnions()" style="color: #000 !important; background-color: #fff !important;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                                <option value="" style="color: #000 !important;" x-text="lang === 'bn' ? 'সকল উপজেলা' : 'All Upazilas'"></option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" style="color: #000 !important;" x-text="lang === 'bn' ? '{{ $upazila->name_bn }}' : '{{ $upazila->name_en }}'"></option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Union (Cascading) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span x-show="lang === 'bn'">ইউনিয়ন</span>
                                <span x-show="lang === 'en'" x-cloak>Union</span>
                            </label>
                            <select x-model="union" @change="loadAreaCodes()" style="color: #000 !important; background-color: #fff !important;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition" :disabled="!upazila">
                                <option value="" style="color: #000 !important;" x-text="lang === 'bn' ? 'সকল ইউনিয়ন' : 'All Unions'"></option>
                                <template x-for="u in unions" :key="u.id">
                                    <option :value="u.id" :x-text="lang === 'bn' ? u.name_bn : u.name_en" style="color: #000 !important;"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Ward -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span x-show="lang === 'bn'">ওয়ার্ড</span>
                                <span x-show="lang === 'en'" x-cloak>Ward</span>
                            </label>
                            <select x-model="ward" style="color: #000 !important; background-color: #fff !important;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                                <option value="" style="color: #000 !important;" x-text="lang === 'bn' ? 'সকল ওয়ার্ড' : 'All Wards'"></option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" style="color: #000 !important;" x-text="lang === 'bn' ? '{{ $ward->name_bn }}' : '{{ $ward->name_en }}'"></option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area Code (Cascading) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span x-show="lang === 'bn'">এরিয়া কোড</span>
                                <span x-show="lang === 'en'" x-cloak>Area Code</span>
                            </label>
                            <select x-model="areaCode" style="color: #000 !important; background-color: #fff !important;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition" :disabled="!union">
                                <option value="" style="color: #000 !important;" x-text="lang === 'bn' ? 'সকল এরিয়া কোড' : 'All Area Codes'"></option>
                                <template x-for="ac in areaCodes" :key="ac.id">
                                    <option :value="ac.id" :x-text="ac.area_code_no + ' - ' + (lang === 'bn' ? ac.vote_center_name_bn : ac.vote_center_name_en)" style="color: #000 !important;"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="flex justify-center pt-4 sm:pt-6">
                    <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-xl transform hover:scale-105 active:scale-95 flex items-center justify-center text-base sm:text-lg font-bold">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span x-show="lang === 'bn'">খুঁজুন</span>
                        <span x-show="lang === 'en'" x-cloak>Search</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div x-show="loading" x-cloak class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            <p class="text-gray-600 mt-4">
                <span x-show="lang === 'bn'">অনুসন্ধান চলছে...</span>
                <span x-show="lang === 'en'" x-cloak>Searching...</span>
            </p>
        </div>

        <!-- Results Table -->
        <div x-show="results.length > 0 && !loading" x-cloak class="bg-white rounded-2xl card-shadow overflow-hidden animate-fade-in">
            <div class="px-4 sm:px-6 py-3 sm:py-4 gradient-bg text-white">
                <h2 class="text-lg sm:text-xl font-bold">
                    <span x-show="lang === 'bn'">অনুসন্ধান ফলাফল (<span x-text="results.length"></span>টি রেকর্ড)</span>
                    <span x-show="lang === 'en'" x-cloak>Search Results (<span x-text="results.length"></span> Records)</span>
                </h2>
            </div>
            <div class="overflow-x-auto mobile-scroll">
                <table class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">সিরিয়াল</span>
                                <span x-show="lang === 'en'" x-cloak>Serial</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">ভোটার আইডি</span>
                                <span x-show="lang === 'en'" x-cloak>Voter ID</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">পিতার নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Father's Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">মাতার নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Mother's Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">পেশা</span>
                                <span x-show="lang === 'en'" x-cloak>Occupation</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span x-show="lang === 'bn'">জন্ম তারিখ</span>
                                <span x-show="lang === 'en'" x-cloak>Date of Birth</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="voter in results" :key="voter.id">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="voter.serial_no"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="lang === 'bn' ? voter.name_bn : voter.name_en"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="voter.voter_id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="lang === 'bn' ? voter.father_name_bn : voter.father_name_en"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="lang === 'bn' ? voter.mother_name_bn : voter.mother_name_en"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="lang === 'bn' ? voter.occupation_bn : voter.occupation_en"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="voter.date_of_birth"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- No Results -->
        <div x-show="searched && results.length === 0 && !loading" x-cloak class="bg-white rounded-2xl shadow-2xl p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                <span x-show="lang === 'bn'">কোনো ফলাফল পাওয়া যায়নি</span>
                <span x-show="lang === 'en'" x-cloak>No Results Found</span>
            </h3>
            <p class="text-gray-600">
                <span x-show="lang === 'bn'">অনুগ্রহ করে আপনার অনুসন্ধান শর্ত পরিবর্তন করে আবার চেষ্টা করুন</span>
                <span x-show="lang === 'en'" x-cloak>Please modify your search criteria and try again</span>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-6 sm:py-8 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm sm:text-base">
                <span x-show="lang === 'bn'">&copy; 2024 সাতক্ষীরা সদর ভোটার তালিকা। সর্বস্বত্ব সংরক্ষিত।</span>
                <span x-show="lang === 'en'" x-cloak>&copy; 2024 Satkhira Sadar Voter List. All rights reserved.</span>
            </p>
            <p class="text-gray-400 text-xs sm:text-sm mt-2">
                <a href="{{ route('admin.login') }}" class="hover:text-purple-400 transition-colors inline-flex items-center gap-1">
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
                lang: 'bn', // Default language
                searchBy: '',
                searchValue: '',
                upazila: '',
                union: '',
                ward: '',
                areaCode: '',
                unions: [],
                areaCodes: [],
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
                    this.areaCode = '';
                    this.unions = [];
                    this.areaCodes = [];

                    if (!this.upazila) return;

                    try {
                        const response = await fetch(`/api/unions/${this.upazila}`);
                        this.unions = await response.json();
                    } catch (error) {
                        console.error('Error loading unions:', error);
                    }
                },

                async loadAreaCodes() {
                    this.areaCode = '';
                    this.areaCodes = [];

                    if (!this.union) return;

                    try {
                        const response = await fetch(`/api/area-codes/${this.union}`);
                        this.areaCodes = await response.json();
                    } catch (error) {
                        console.error('Error loading area codes:', error);
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

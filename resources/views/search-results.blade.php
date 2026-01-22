<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অনুসন্ধান ফলাফল - সাতক্ষীরা-২ ভোটার তালিকা</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Bangla -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
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
                <a href="{{ route('home') }}" class="flex items-center mb-4 md:mb-0">
                    <div class="bg-white text-purple-600 p-3 rounded-full mr-4 shadow-lg">
                        <i class="fas fa-vote-yea text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">সাতক্ষীরা-২ নির্বাচনী এলাকা</h1>
                        <p class="text-purple-200 text-sm">ভোটার তালিকা অনুসন্ধান সিস্টেম</p>
                    </div>
                </a>
                
                <a href="{{ route('home') }}" class="bg-white/20 text-white px-4 py-2 rounded-full text-sm hover:bg-white/30 transition flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    হোম
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">
        <!-- Quick Search Bar -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Pass search type if available, auto-detect otherwise -->
                @if($searchType)
                    <input type="hidden" name="search_type" value="{{ $searchType }}">
                @endif
                <div class="flex-grow">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="query" 
                               value="{{ $query ?? '' }}"
                               placeholder="নাম, ভোটার আইডি (English/বাংলা), সিরিয়াল নম্বর দিয়ে খুঁজুন..."
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition text-lg">
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>English বা বাংলা যেকোনো ভাষায় নম্বর দিয়ে সার্চ করতে পারবেন</p>
                </div>
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-indigo-700 transition flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    অনুসন্ধান
                </button>
                <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300 transition flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i>
                    ফিল্টার
                </a>
            </form>
        </div>

        <!-- Search Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-search text-purple-600 mr-2"></i>
                        অনুসন্ধান ফলাফল
                    </h2>
                    <p class="text-gray-600 mt-1">
                        @if($query)
                            "{{ $query }}" এর জন্য 
                        @endif
                        <span class="font-semibold text-purple-600">{{ $voters->total() ?? 0 }}</span> টি ফলাফল পাওয়া গেছে
                    </p>
                </div>
                
                <a href="{{ route('home') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition flex items-center">
                    <i class="fas fa-sliders-h mr-2"></i>
                    অ্যাডভান্সড সার্চ
                </a>
            </div>
        </div>

        <!-- Results -->
        @if($voters->count() > 0)
            <div class="grid gap-6">
                @foreach($voters as $voter)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100">
                        <!-- Card Header - Center Info -->
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-purple-200">কেন্দ্র</p>
                                        <p class="font-bold text-lg">{{ $voter->center_no }} - {{ $voter->center_name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-purple-200">সিরিয়াল</p>
                                    <p class="font-bold text-2xl">{{ $voter->serial_no }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- Name & Voter ID -->
                            <div class="flex items-start mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $voter->name }}</h3>
                                    <div class="flex items-center text-purple-600">
                                        <i class="fas fa-id-card mr-2"></i>
                                        <span class="font-mono font-semibold">{{ $voter->voter_id }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($voter->gender == 'পুরুষ')
                                        <span class="inline-flex items-center bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                            <i class="fas fa-mars mr-1"></i>
                                            পুরুষ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm font-medium">
                                            <i class="fas fa-venus mr-1"></i>
                                            মহিলা
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- DOB -->
                                <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-birthday-cake text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">জন্ম তারিখ</p>
                                        <p class="font-semibold text-gray-800">{{ $voter->date_of_birth ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Occupation -->
                                <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-briefcase text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">পেশা</p>
                                        <p class="font-semibold text-gray-800">{{ $voter->occupation ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Father Name -->
                                <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-male text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">পিতার নাম</p>
                                        <p class="font-semibold text-gray-800">{{ $voter->father_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Mother Name -->
                                <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-female text-pink-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">মাতার নাম</p>
                                        <p class="font-semibold text-gray-800">{{ $voter->mother_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Section -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-red-600"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-xs text-gray-500 mb-1">ঠিকানা</p>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <span class="inline-flex items-center bg-white text-gray-700 text-xs px-3 py-1 rounded-full shadow-sm">
                                                <i class="fas fa-city text-purple-500 mr-1"></i>
                                                {{ $voter->upazila }}
                                            </span>
                                            <span class="inline-flex items-center bg-white text-gray-700 text-xs px-3 py-1 rounded-full shadow-sm">
                                                <i class="fas fa-landmark text-blue-500 mr-1"></i>
                                                {{ $voter->union }}
                                            </span>
                                            <span class="inline-flex items-center bg-white text-gray-700 text-xs px-3 py-1 rounded-full shadow-sm">
                                                <i class="fas fa-map text-green-500 mr-1"></i>
                                                ওয়ার্ড: {{ $voter->ward }}
                                            </span>
                                            <span class="inline-flex items-center bg-white text-gray-700 text-xs px-3 py-1 rounded-full shadow-sm">
                                                <i class="fas fa-home text-orange-500 mr-1"></i>
                                                {{ $voter->area_code }} - {{ $voter->area_name }}
                                            </span>
                                        </div>
                                        @if($voter->address)
                                            <p class="text-sm text-gray-600">{{ $voter->address }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $voters->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">কোন ফলাফল পাওয়া যায়নি</h3>
                <p class="text-gray-600 mb-6">
                    দুঃখিত, 
                    @if($query)"{{ $query }}" এর জন্য @endif
                    কোন ভোটার তথ্য পাওয়া যায়নি।
                </p>
                <div class="space-y-2 text-sm text-gray-500">
                    <p><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>বানান পরীক্ষা করুন</p>
                    <p><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>অন্য কীওয়ার্ড দিয়ে চেষ্টা করুন</p>
                    <p><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>ফিল্টার পরিবর্তন করুন</p>
                </div>
                <a href="{{ route('home') }}" class="inline-block mt-6 bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    আবার চেষ্টা করুন
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-gray-400 text-sm">
            <p>© {{ date('Y') }} সাতক্ষীরা-২ নির্বাচনী এলাকা। সর্বস্বত্ব সংরক্ষিত।</p>
            <p class="mt-2 text-gray-500">Developed by <span class="text-purple-400 font-medium">Mir Javed Jeetu</span></p>
        </div>
    </footer>
</body>
</html>

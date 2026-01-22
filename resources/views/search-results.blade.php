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
            <div class="grid gap-4">
                @foreach($voters as $voter)
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex flex-col md:flex-row justify-between">
                            <div class="flex-grow">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $voter->name }}</h3>
                                        <p class="text-purple-600 font-mono text-sm">ভোটার আইডি: {{ $voter->voter_id }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">পিতার নাম:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->father_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">মাতার নাম:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->mother_name }}</span>
                                    </div>
                                    @if($voter->spouse_name)
                                    <div>
                                        <span class="text-gray-500">স্বামী/স্ত্রীর নাম:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->spouse_name }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-500">জন্ম তারিখ:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->date_of_birth }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">পেশা:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->occupation }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">লিঙ্গ:</span>
                                        <span class="text-gray-800 font-medium ml-1">{{ $voter->gender }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $voter->center_name }}
                                    </span>
                                    @if($voter->ward_no)
                                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                        <i class="fas fa-map mr-1"></i>
                                        ওয়ার্ড: {{ $voter->ward_no }}
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                                        <i class="fas fa-hashtag mr-1"></i>
                                        সিরিয়াল: {{ $voter->serial_no }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($voter->address)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span class="text-gray-500 text-sm"><i class="fas fa-map-marker-alt mr-1"></i> ঠিকানা:</span>
                            <span class="text-gray-700 text-sm ml-1">{{ $voter->address }}</span>
                        </div>
                        @endif
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

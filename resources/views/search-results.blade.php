<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অনুসন্ধান ফলাফল - সাতক্ষীরা-২ ভোটার তালিকা</title>
    
    <!-- Cache Control - Force fresh content -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=5">
    
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
        <!-- Quick Search Bar with Preserved Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('search') }}" method="GET" class="space-y-4">
                <!-- Keep current filters as hidden fields -->
                @if(!empty($currentFilters['upazila']))
                    <input type="hidden" name="upazila" value="{{ $currentFilters['upazila'] }}">
                @endif
                @if(!empty($currentFilters['union']))
                    <input type="hidden" name="union" value="{{ $currentFilters['union'] }}">
                @endif
                @if(!empty($currentFilters['ward']))
                    <input type="hidden" name="ward" value="{{ $currentFilters['ward'] }}">
                @endif
                @if(!empty($currentFilters['area_code']))
                    <input type="hidden" name="area_code" value="{{ $currentFilters['area_code'] }}">
                @endif
                @if(!empty($currentFilters['gender']))
                    <input type="hidden" name="gender" value="{{ $currentFilters['gender'] }}">
                @endif
                @if($searchType)
                    <input type="hidden" name="search_type" value="{{ $searchType }}">
                @endif
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="query" 
                                   value="{{ $query ?? '' }}"
                                   placeholder="নাম, ভোটার আইডি, সিরিয়াল নম্বর দিয়ে খুঁজুন..."
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition text-lg">
                        </div>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-indigo-700 transition flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>
                        অনুসন্ধান
                    </button>
                    <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300 transition flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>
                        নতুন সার্চ
                    </a>
                </div>
                
                <!-- Current Filters Display -->
                @if(!empty($currentFilters['upazila']) || !empty($currentFilters['union']) || !empty($currentFilters['ward']) || !empty($currentFilters['area_code']) || !empty($currentFilters['gender']))
                <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-500"><i class="fas fa-filter mr-1"></i>সক্রিয় ফিল্টার:</span>
                    @if(!empty($currentFilters['upazila']))
                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs">{{ $currentFilters['upazila'] }}</span>
                    @endif
                    @if(!empty($currentFilters['union']))
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">{{ $currentFilters['union'] }}</span>
                    @endif
                    @if(!empty($currentFilters['ward']))
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">ওয়ার্ড: {{ $currentFilters['ward'] }}</span>
                    @endif
                    @if(!empty($currentFilters['area_code']))
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">এলাকা কোড: {{ $currentFilters['area_code'] }}</span>
                    @endif
                    @if(!empty($currentFilters['gender']))
                        <span class="bg-pink-100 text-pink-700 px-2 py-1 rounded-full text-xs">{{ $currentFilters['gender'] }}</span>
                    @endif
                </div>
                @endif
            </form>
        </div>

        <!-- Search Summary & Result Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-search text-purple-600 mr-2"></i>
                        অনুসন্ধান ফলাফল
                    </h2>
                    <p class="text-gray-600 mt-1">
                        @if($query)
                            "{{ $query }}" এর জন্য 
                        @endif
                        <span class="font-semibold text-purple-600">@bengali($voters->total() ?? 0)</span> টি ফলাফল পাওয়া গেছে
                    </p>
                </div>
                
                <!-- Result Page Filters - Server Side -->
                <form action="{{ route('search') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                    <!-- Preserve existing filters -->
                    @if($query)<input type="hidden" name="query" value="{{ $query }}">@endif
                    @if($searchType)<input type="hidden" name="search_type" value="{{ $searchType }}">@endif
                    @if(!empty($currentFilters['upazila']))<input type="hidden" name="upazila" value="{{ $currentFilters['upazila'] }}">@endif
                    @if(!empty($currentFilters['union']))<input type="hidden" name="union" value="{{ $currentFilters['union'] }}">@endif
                    @if(!empty($currentFilters['ward']))<input type="hidden" name="ward" value="{{ $currentFilters['ward'] }}">@endif
                    @if(!empty($currentFilters['area_code']))<input type="hidden" name="area_code" value="{{ $currentFilters['area_code'] }}">@endif
                    @if(!empty($currentFilters['gender']))<input type="hidden" name="gender" value="{{ $currentFilters['gender'] }}">@endif
                    
                    <!-- Center Filter - Hidden temporarily -->
                    {{-- @if(count($centers) >= 1)
                    <div class="relative">
                        <select name="center" onchange="this.form.submit()"
                                class="appearance-none bg-purple-50 border border-purple-200 text-purple-700 px-4 py-2 pr-8 rounded-lg text-sm focus:outline-none focus:border-purple-500 cursor-pointer">
                            <option value="">সকল কেন্দ্র ({{ count($centers) }})</option>
                            @foreach($centers as $centerName => $displayName)
                                <option value="{{ $centerName }}" {{ ($currentFilters['center'] ?? '') == $centerName ? 'selected' : '' }}>{{ $displayName }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-purple-500 text-xs pointer-events-none"></i>
                    </div>
                    @endif --}}
                    
                    <!-- Birth Year Filter -->
                    @if(count($birthYears) >= 1)
                    <div class="relative">
                        <select name="birth_year" onchange="this.form.submit()"
                                class="appearance-none bg-green-50 border border-green-200 text-green-700 px-4 py-2 pr-8 rounded-lg text-sm focus:outline-none focus:border-green-500 cursor-pointer">
                            <option value="">সকল জন্ম সাল ({{ count($birthYears) }})</option>
                            @foreach($birthYears as $year)
                                <option value="{{ $year }}" {{ ($currentFilters['birth_year'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-green-500 text-xs pointer-events-none"></i>
                    </div>
                    @endif
                    
                    <!-- Clear Additional Filters -->
                    @if(!empty($currentFilters['center']) || !empty($currentFilters['birth_year']))
                    <a href="{{ route('search', array_filter([
                        'query' => $query,
                        'search_type' => $searchType,
                        'upazila' => $currentFilters['upazila'] ?? null,
                        'union' => $currentFilters['union'] ?? null,
                        'ward' => $currentFilters['ward'] ?? null,
                        'area_code' => $currentFilters['area_code'] ?? null,
                        'gender' => $currentFilters['gender'] ?? null,
                    ])) }}" class="bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-200 transition">
                        <i class="fas fa-times mr-1"></i>
                        ফিল্টার মুছুন
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Results -->
        @if($voters->count() > 0)
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-1">
                @foreach($voters as $index => $voter)
                    <div id="voter-card-{{ $voter->id }}" 
                         class="voter-card bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 overflow-hidden">
                        <!-- Card Header - Center Info -->
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-4 py-3">
                            <div class="text-white">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-medium">কেন্দ্র নং:</span>
                                    <span class="bg-white/20 px-2 py-0.5 rounded">@bengali($voter->center_no)</span>
                                </div>
                                <div class="text-lg font-semibold mt-1">{{ $voter->center_name }}</div>
                            </div>
                        </div>
                        
                        <!-- Card Body - Voter Info -->
                        <div class="p-4 space-y-2">
                            <!-- Serial No -->
                            <div class="flex items-center text-sm border-b border-gray-100 pb-2">
                                <span class="text-gray-500 w-24">সিরিয়াল নং:</span>
                                <span class="font-bold text-gray-900 text-lg">@bengali($voter->serial_no)</span>
                            </div>
                            
                            <!-- Name -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">নাম:</span>
                                <span class="font-bold text-gray-900 text-lg">{{ $voter->name }}</span>
                                <span class="ml-2">{{ $voter->gender == 'পুরুষ' ? '👨' : ($voter->gender == 'মহিলা' ? '👩' : '🧑') }}</span>
                            </div>
                            
                            <!-- Voter ID -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">ভোটার নং:</span>
                                <span class="font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">@bengali($voter->voter_id)</span>
                            </div>
                            
                            <!-- Father -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">পিতা:</span>
                                <span class="text-gray-700">{{ $voter->father_name ?? 'N/A' }}</span>
                            </div>
                            
                            <!-- Mother -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">মাতা:</span>
                                <span class="text-gray-700">{{ $voter->mother_name ?? 'N/A' }}</span>
                            </div>
                            
                            <!-- Occupation -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">পেশা:</span>
                                <span class="text-gray-700">{{ $voter->occupation ?? 'N/A' }}</span>
                            </div>
                            
                            <!-- Date of Birth -->
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 w-24">জন্ম তারিখ:</span>
                                <span class="text-gray-700">@bengali($voter->date_of_birth ?? 'N/A')</span>
                            </div>
                            
                            <!-- Address -->
                            <div class="flex items-start text-sm pt-2 border-t border-gray-100">
                                <span class="text-gray-500 w-24 flex-shrink-0">ঠিকানা:</span>
                                <span class="text-gray-700">{{ $voter->area_name ?? '' }}(@bengali($voter->area_code)), ওয়ার্ড-@bengali($voter->ward), {{ $voter->union }}, {{ $voter->upazila }}, সাতক্ষীরা।</span>
                            </div>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border-t border-gray-100">
                            <button onclick="shareVoter({{ $voter->id }}, '{{ addslashes($voter->name) }}')" 
                                    class="flex items-center gap-1 text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-full transition">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </button>
                            <button onclick="shareVoterNative({{ $voter->id }})" 
                                    class="flex items-center gap-1 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-full transition">
                                <i class="fas fa-share-alt"></i> শেয়ার
                            </button>
                            <button onclick="copyVoterData({{ $voter->id }})" 
                                    class="flex items-center gap-1 text-xs bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-full transition">
                                <i class="fas fa-copy"></i> কপি
                            </button>
                        </div>
                        
                        <!-- Hidden data for sharing -->
                        <script type="application/json" id="voter-data-{{ $voter->id }}">
                            {
                                "id": {{ $voter->id }},
                                "name": "{{ $voter->name }}",
                                "voter_id": "{{ $voter->voter_id }}",
                                "serial_no": "{{ $voter->serial_no }}",
                                "gender": "{{ $voter->gender }}",
                                "dob": "{{ $voter->date_of_birth ?? 'N/A' }}",
                                "occupation": "{{ $voter->occupation ?? 'N/A' }}",
                                "father": "{{ $voter->father_name ?? 'N/A' }}",
                                "mother": "{{ $voter->mother_name ?? 'N/A' }}",
                                "center_no": "{{ $voter->center_no }}",
                                "center_name": "{{ $voter->center_name }}",
                                "upazila": "{{ $voter->upazila }}",
                                "union": "{{ $voter->union }}",
                                "ward": "{{ $voter->ward }}",
                                "area_name": "{{ $voter->area_name ?? '' }}",
                                "area_code": "{{ $voter->area_code }}"
                            }
                        </script>
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

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
        <span id="toast-message"></span>
    </div>

    <script>
        // Show toast notification
        function showToast(message, duration = 2000) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, duration);
        }

        // Get voter data from JSON script tag
        function getVoterData(voterId) {
            const script = document.getElementById('voter-data-' + voterId);
            if (script) {
                return JSON.parse(script.textContent);
            }
            return null;
        }

        // Format voter data as text
        function formatVoterText(data) {
            return `📋 *ভোটার তথ্য*
━━━━━━━━━━━━━━━
🏢 *কেন্দ্র নং:* ${data.center_no}
🏛️ *কেন্দ্র:* ${data.center_name}
📝 *সিরিয়াল নং:* ${data.serial_no}
👤 *নাম:* ${data.name}
🆔 *ভোটার নং:* ${data.voter_id}
👨 *পিতা:* ${data.father}
👩 *মাতা:* ${data.mother}
💼 *পেশা:* ${data.occupation}
📅 *জন্ম তারিখ:* ${data.dob}
📍 *ঠিকানা:* ${data.area_name}(${data.area_code}), ওয়ার্ড-${data.ward}, ${data.union}, ${data.upazila}, সাতক্ষীরা।
━━━━━━━━━━━━━━━
🌐 সাতক্ষীরা-২ ভোটার তালিকা`;
        }

        // Share to WhatsApp
        function shareVoter(voterId, voterName) {
            const data = getVoterData(voterId);
            if (!data) {
                showToast('ডাটা পাওয়া যায়নি');
                return;
            }
            
            const text = formatVoterText(data);
            const whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(text);
            window.open(whatsappUrl, '_blank');
        }

        // Native share API
        function shareVoterNative(voterId) {
            const data = getVoterData(voterId);
            if (!data) {
                showToast('ডাটা পাওয়া যায়নি');
                return;
            }
            
            const text = formatVoterText(data);
            
            if (navigator.share) {
                navigator.share({
                    title: 'ভোটার তথ্য - ' + data.name,
                    text: text,
                    url: window.location.href
                }).then(() => {
                    showToast('শেয়ার করা হয়েছে');
                }).catch((err) => {
                    // User cancelled or error
                    if (err.name !== 'AbortError') {
                        copyToClipboard(text);
                        showToast('লিংক কপি করা হয়েছে');
                    }
                });
            } else {
                // Fallback - copy to clipboard
                copyToClipboard(text);
                showToast('টেক্সট কপি করা হয়েছে');
            }
        }

        // Copy voter data to clipboard
        function copyVoterData(voterId) {
            const data = getVoterData(voterId);
            if (!data) {
                showToast('ডাটা পাওয়া যায়নি');
                return;
            }
            
            const text = formatVoterText(data);
            copyToClipboard(text);
            showToast('✓ কপি করা হয়েছে');
        }

        // Helper function to copy to clipboard
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Copy failed:', err);
                }
                document.body.removeChild(textArea);
            }
        }
    </script>
</body>
</html>

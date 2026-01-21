<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সকল ভোটার - সাতক্ষীরা-২ আসন ভোটার তথ্য</title>
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
                        <span x-show="lang === 'bn'">সকল ভোটার তালিকা</span>
                        <span x-show="lang === 'en'" x-cloak>All Voters List</span>
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600">
                        <span x-show="lang === 'bn'">ড্যাশবোর্ড</span>
                        <span x-show="lang === 'en'" x-cloak>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.voters') }}" class="text-purple-600 hover:text-purple-800">
                        <span x-show="lang === 'bn'">সকল ভোটার</span>
                        <span x-show="lang === 'en'" x-cloak>All Voters</span>
                    </a>
                    <a href="{{ route('admin.upload') }}" class="text-gray-600 hover:text-purple-600">
                        <span x-show="lang === 'bn'">আপলোড</span>
                        <span x-show="lang === 'en'" x-cloak>Upload</span>
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
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex justify-between items-center">
                <h2 class="text-xl font-bold">
                    <span x-show="lang === 'bn'">মোট ভোটার: {{ number_format($voters->total()) }}</span>
                    <span x-show="lang === 'en'" x-cloak>Total Voters: {{ number_format($voters->total()) }}</span>
                </h2>
                
                <form method="POST" action="{{ route('admin.reset.voters') }}" 
                      onsubmit="return confirm('আপনি কি নিশ্চিত সব ভোটার ডেটা মুছে ফেলতে চান? এটি পূর্বাবস্থায় ফেরানো যাবে না!');" 
                      class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span x-show="lang === 'bn'">সব ডেটা মুছুন</span>
                        <span x-show="lang === 'en'" x-cloak>Delete All Data</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">সিরিয়াল</span>
                                <span x-show="lang === 'en'" x-cloak>Serial</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">ভোটার আইডি</span>
                                <span x-show="lang === 'en'" x-cloak>Voter ID</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">পিতার নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Father's Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">মাতার নাম</span>
                                <span x-show="lang === 'en'" x-cloak>Mother's Name</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <span x-show="lang === 'bn'">জন্ম তারিখ</span>
                                <span x-show="lang === 'en'" x-cloak>Date of Birth</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($voters as $voter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->serial_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span x-show="lang === 'bn'">{{ $voter->name_bn }}</span>
                                    <span x-show="lang === 'en'" x-cloak>{{ $voter->name_en }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->voter_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-show="lang === 'bn'">{{ $voter->father_name_bn }}</span>
                                    <span x-show="lang === 'en'" x-cloak>{{ $voter->father_name_en }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-show="lang === 'bn'">{{ $voter->mother_name_bn }}</span>
                                    <span x-show="lang === 'en'" x-cloak>{{ $voter->mother_name_en }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->date_of_birth }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50">
                {{ $voters->links() }}
            </div>
        </div>
    </div>
</body>
</html>

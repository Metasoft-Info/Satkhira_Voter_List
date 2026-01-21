<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আপলোড - সাতক্ষীরা-২ আসন ভোটার তথ্য</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">সাতক্ষীরা ভোটার তালিকা - অ্যাডমিন</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600">ড্যাশবোর্ড</a>
                    <a href="{{ route('admin.upload') }}" class="text-purple-600 hover:text-purple-800">আপলোড</a>
                    <a href="{{ route('voters.index') }}" target="_blank" class="text-gray-600 hover:text-purple-600">পাবলিক সাইট</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">লগআউট</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="ml-3 text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="ml-3 text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Upload Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">ভোটার ডেটা আপলোড</h2>
                <p class="text-gray-600">Excel ফাইল আপলোড করে সম্পূর্ণ ডেটাবেস আপডেট করুন</p>
            </div>

            <!-- Warning -->
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">⚠️ গুরুত্বপূর্ণ সতর্কতা</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            এই অপশনটি ব্যবহার করলে বিদ্যমান সকল ভোটার ডেটা মুছে যাবে এবং নতুন ডেটা দিয়ে প্রতিস্থাপিত হবে। 
                            ফাইলটি আপলোড করার আগে নিশ্চিত হয়ে নিন।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('admin.upload.submit') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- File Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excel ফাইল নির্বাচন করুন (.xlsx, .xls)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                    <span>ফাইল আপলোড করুন</span>
                                    <input id="excel_file" name="excel_file" type="file" class="sr-only" required accept=".xlsx,.xls" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">অথবা ড্র্যাগ এন্ড ড্রপ</p>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name">সর্বোচ্চ সাইজ: 50MB</p>
                        </div>
                    </div>
                    @error('excel_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Format Instructions -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">📋 ফাইল ফরম্যাট নির্দেশনা:</h3>
                    <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                        <li>কলাম ক্রম: সিরিয়াল নং, নাম, ভোটার আইডি, পিতার নাম, মাতার নাম, পেশা, জন্ম তারিখ, ঠিকানা, ইউনিয়ন/ওয়ার্ড, উপজেলা, জেলা</li>
                        <li>প্রথম সারিতে হেডার থাকতে হবে</li>
                        <li>সকল তথ্য বাংলায় থাকতে হবে</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition shadow-lg" onclick="return confirm('আপনি কি নিশ্চিত? সকল বর্তমান ডেটা মুছে যাবে!')">
                        আপলোড শুরু করুন
                    </button>
                </div>
            </form>
        </div>

        <!-- Last Upload Info -->
        @if($lastUpload && $lastUpload->updated_at)
            <div class="mt-6 bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">শেষ আপডেট:</span> 
                    {{ $lastUpload->updated_at->format('d M Y, h:i A') }}
                </p>
            </div>
        @endif
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = fileName;
            }
        }
    </script>
</body>
</html>

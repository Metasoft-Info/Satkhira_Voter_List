@extends('layouts.admin')

@section('title', 'ডেটা আপলোড')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">📤 ডেটা আপলোড</h1>
    </div>

    <!-- Current Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-600">মোট ভোটার</p>
            <p class="text-2xl font-bold text-blue-800">{{ number_format($stats['total'] ?? 0) }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-600">সর্বশেষ আপডেট</p>
            <p class="text-lg font-semibold text-green-800">{{ $stats['lastUpdate'] ?? 'কোন ডেটা নেই' }}</p>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📁 এক্সেল ফাইল আপলোড</h2>
        
        <form action="{{ route('admin.upload.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Upload Mode Selection -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">আপলোড মোড নির্বাচন করুন:</label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Smart Mode -->
                    <label class="relative flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <input type="radio" name="upload_mode" value="smart" class="mt-1 mr-3" checked>
                        <div>
                            <span class="block font-semibold text-green-700">🔄 স্মার্ট আপলোড (প্রস্তাবিত)</span>
                            <span class="text-sm text-gray-600">
                                শুধু নতুন ডেটা যোগ হবে এবং পরিবর্তিত ডেটা আপডেট হবে। 
                                <br>পুরাতন ডেটা মুছবে না।
                            </span>
                        </div>
                    </label>
                    
                    <!-- Replace Mode -->
                    <label class="relative flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-400 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                        <input type="radio" name="upload_mode" value="replace" class="mt-1 mr-3">
                        <div>
                            <span class="block font-semibold text-red-700">⚠️ রিপ্লেস আপলোড</span>
                            <span class="text-sm text-gray-600">
                                সব পুরাতন ডেটা মুছে নতুন ফাইলের ডেটা দিয়ে প্রতিস্থাপন করবে।
                                <br><strong class="text-red-600">সতর্কতা: পুরাতন ডেটা হারাবে!</strong>
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ডেটা ফাইল (.xlsx, .csv)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                <span>ফাইল নির্বাচন করুন</span>
                                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="sr-only" required id="excel_file">
                            </label>
                            <p class="pl-1">অথবা ড্র্যাগ করে ছেড়ে দিন</p>
                        </div>
                        <p class="text-xs text-gray-500">XLSX, XLS (সর্বোচ্চ ৫০,০০০ রেকর্ড) | CSV (৫ লক্ষ+ রেকর্ড সাপোর্ট)</p>
                        <p class="text-xs text-green-600 font-medium">💡 বড় ফাইলের জন্য CSV ব্যবহার করুন (Excel → Save As → CSV UTF-8)</p>
                        <p id="file_name" class="text-sm font-medium text-purple-600 mt-2"></p>
                    </div>
                </div>
            </div>

            <!-- Expected Columns Info -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-800 mb-2">📋 প্রত্যাশিত কলাম অর্ডার:</h3>
                <div class="text-sm text-yellow-700 grid grid-cols-2 md:grid-cols-4 gap-2">
                    <span>1. ক্রমিক নং</span>
                    <span>2. উপজেলা</span>
                    <span>3. ইউনিয়ন</span>
                    <span>4. ওয়ার্ড</span>
                    <span>5. এলাকা কোড</span>
                    <span>6. এলাকার নাম</span>
                    <span>7. লিঙ্গ</span>
                    <span>8. কেন্দ্র নং</span>
                    <span>9. কেন্দ্রের নাম</span>
                    <span>10. (খালি)</span>
                    <span>11. নাম</span>
                    <span>12. ভোটার আইডি</span>
                    <span>13. পিতার নাম</span>
                    <span>14. মাতার নাম</span>
                    <span>15. পেশা</span>
                    <span>16. জন্ম তারিখ</span>
                    <span>17. ঠিকানা</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition shadow-lg">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    আপলোড করুন
                </span>
            </button>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
        <h2 class="text-lg font-semibold text-red-600 mb-4">⚠️ ডেঞ্জার জোন</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 font-medium">সকল ভোটার ডেটা মুছে ফেলুন</p>
                <p class="text-sm text-gray-500">এটি সকল ভোটার তথ্য স্থায়ীভাবে মুছে ফেলবে।</p>
            </div>
            <form action="{{ route('admin.reset.voters') }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত? এটি সকল ভোটার ডেটা মুছে ফেলবে!');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    রিসেট করুন
                </button>
            </form>
        </div>
    </div>

    <!-- English Transliteration -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold text-blue-600 mb-4">🔤 ইংরেজি অনুবাদ</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 font-medium">বাংলা নাম ইংরেজিতে রূপান্তর</p>
                <p class="text-sm text-gray-500">এটি সকল ভোটারের বাংলা নাম ইংরেজিতে (transliteration) রূপান্তর করবে। এতে ইংরেজিতে সার্চ করা যাবে।</p>
            </div>
            <form action="{{ route('admin.transliterate') }}" method="POST" onsubmit="return confirm('এটি কিছু সময় নিতে পারে। আপনি কি চালিয়ে যেতে চান?');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    রূপান্তর করুন
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('excel_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('file_name').textContent = fileName ? '📄 ' + fileName : '';
});
</script>
@endsection

@extends('layouts.admin')

@section('title', 'ব্যানার ম্যানেজমেন্ট')
@section('header', 'ব্যানার ম্যানেজমেন্ট')

@section('content')
<div class="max-w-6xl mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add New Banner Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600">
                    <h2 class="text-lg font-bold text-white">
                        <i class="fas fa-plus-circle mr-2"></i>
                        নতুন ব্যানার যোগ করুন
                    </h2>
                </div>

                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">ব্যানার ছবি *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-purple-500 transition cursor-pointer"
                             onclick="document.getElementById('banner_image').click()">
                            <input type="file" name="image" id="banner_image" accept="image/*" required class="hidden" onchange="previewImage(this)">
                            <div id="image-preview-container">
                                <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">ছবি নির্বাচন করুন</p>
                            </div>
                            <img id="image-preview" class="hidden max-h-32 mx-auto rounded" alt="Preview">
                        </div>
                    </div>

                    <!-- Title Bengali -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">শিরোনাম (বাংলা)</label>
                        <input type="text" name="title_bn" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500" placeholder="সাতক্ষীরা-২ নির্বাচনী এলাকা">
                    </div>

                    <!-- Title English -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">শিরোনাম (ইংরেজি)</label>
                        <input type="text" name="title_en" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500" placeholder="Satkhira-2 Constituency">
                    </div>

                    <!-- Subtitle -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">সাব-শিরোনাম</label>
                        <input type="text" name="subtitle" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500" placeholder="ভোটার তালিকা ২০২৫">
                    </div>

                    <!-- Link URL -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">লিঙ্ক URL</label>
                        <input type="url" name="link_url" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500" placeholder="https://...">
                    </div>

                    <!-- Order -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">ক্রম নম্বর</label>
                        <input type="number" name="order" value="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500">
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:from-purple-700 hover:to-indigo-700 transition">
                        <i class="fas fa-plus mr-2"></i>
                        ব্যানার যোগ করুন
                    </button>
                </form>
            </div>
        </div>

        <!-- Existing Banners -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-medium text-gray-800">
                        <i class="fas fa-images mr-2 text-purple-600"></i>
                        বর্তমান ব্যানারসমূহ
                    </h2>
                </div>

                <div class="p-6">
                    @if($banners->count() > 0)
                        <div class="space-y-4">
                            @foreach($banners as $banner)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg {{ !$banner->is_active ? 'opacity-50' : '' }}">
                                    <!-- Image -->
                                    <div class="flex-shrink-0">
                                        @if($banner->image_path)
                                            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                                 alt="{{ $banner->title_bn }}" 
                                                 class="w-32 h-20 object-cover rounded-lg">
                                        @else
                                            <div class="w-32 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-grow">
                                        <h3 class="font-medium text-gray-800">{{ $banner->title_bn ?: 'শিরোনাম নেই' }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $banner->title_en }}</p>
                                        @if($banner->subtitle)
                                            <p class="text-gray-400 text-xs mt-1">{{ $banner->subtitle }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                <i class="fas fa-{{ $banner->is_active ? 'check' : 'times' }} mr-1"></i>
                                                {{ $banner->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </span>
                                            <span class="text-xs text-gray-400">ক্রম: {{ $banner->order }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg {{ $banner->is_active ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition" title="{{ $banner->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                                <i class="fas fa-{{ $banner->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.banners.delete', $banner) }}" method="POST" onsubmit="return confirm('আপনি কি এই ব্যানার মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="মুছে ফেলুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-images text-4xl mb-4 text-gray-300"></i>
                            <p>কোন ব্যানার নেই</p>
                            <p class="text-sm">বাম দিকের ফর্ম ব্যবহার করে নতুন ব্যানার যোগ করুন</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            container.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

@extends('layouts.admin')

@section('title', 'ব্রেকিং নিউজ')
@section('header', 'ব্রেকিং নিউজ ম্যানেজমেন্ট')

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
        <!-- Add New Breaking News Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-6">
                <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-pink-600">
                    <h2 class="text-lg font-bold text-white">
                        <i class="fas fa-bullhorn mr-2"></i>
                        নতুন ব্রেকিং নিউজ
                    </h2>
                </div>

                <form action="{{ route('admin.breaking-news.store') }}" method="POST" class="p-6">
                    @csrf
                    
                    <!-- Content Bengali -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">নিউজ (বাংলা) *</label>
                        <textarea name="content_bn" rows="3" required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500"
                                  placeholder="ভোটার তালিকা আপডেট করা হয়েছে..."></textarea>
                    </div>

                    <!-- Content English -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">নিউজ (ইংরেজি)</label>
                        <textarea name="content_en" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500"
                                  placeholder="Voter list has been updated..."></textarea>
                    </div>

                    <!-- Order -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">ক্রম নম্বর</label>
                        <input type="number" name="order" value="0" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500">
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-pink-600 text-white py-2 px-4 rounded-lg font-medium hover:from-red-700 hover:to-pink-700 transition">
                        <i class="fas fa-plus mr-2"></i>
                        নিউজ যোগ করুন
                    </button>
                </form>
            </div>
        </div>

        <!-- Existing Breaking News -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-800">
                        <i class="fas fa-newspaper mr-2 text-red-600"></i>
                        বর্তমান ব্রেকিং নিউজ
                    </h2>
                    
                    <!-- Preview Marquee -->
                    @if($breakingNews->where('is_active', true)->count() > 0)
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-eye mr-1"></i>
                            সক্রিয়: {{ $breakingNews->where('is_active', true)->count() }}
                        </span>
                    @endif
                </div>

                <!-- Live Preview -->
                @if($breakingNews->where('is_active', true)->count() > 0)
                    <div class="px-6 py-3 bg-red-600 text-white overflow-hidden">
                        <div class="flex items-center">
                            <span class="flex-shrink-0 bg-white text-red-600 px-3 py-1 rounded text-sm font-bold mr-4 animate-pulse">
                                ব্রেকিং
                            </span>
                            <div class="overflow-hidden flex-grow">
                                <div class="whitespace-nowrap animate-marquee">
                                    @foreach($breakingNews->where('is_active', true) as $news)
                                        <span class="mx-8">{{ $news->content_bn }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="p-6">
                    @if($breakingNews->count() > 0)
                        <div class="space-y-3">
                            @foreach($breakingNews as $news)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg {{ !$news->is_active ? 'opacity-50' : '' }}">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $news->is_active ? 'bg-red-100' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-bullhorn {{ $news->is_active ? 'text-red-600' : 'text-gray-400' }}"></i>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow min-w-0">
                                        <p class="text-gray-800 font-medium">{{ $news->content_bn }}</p>
                                        @if($news->content_en)
                                            <p class="text-gray-500 text-sm mt-1">{{ $news->content_en }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $news->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                <i class="fas fa-{{ $news->is_active ? 'check' : 'times' }} mr-1"></i>
                                                {{ $news->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $news->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        <form action="{{ route('admin.breaking-news.toggle', $news) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg {{ $news->is_active ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition" title="{{ $news->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                                <i class="fas fa-{{ $news->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.breaking-news.delete', $news) }}" method="POST" onsubmit="return confirm('আপনি কি এই নিউজ মুছে ফেলতে চান?')">
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
                            <i class="fas fa-newspaper text-4xl mb-4 text-gray-300"></i>
                            <p>কোন ব্রেকিং নিউজ নেই</p>
                            <p class="text-sm">বাম দিকের ফর্ম ব্যবহার করে নতুন নিউজ যোগ করুন</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes marquee {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
.animate-marquee {
    animation: marquee 15s linear infinite;
}
</style>
@endsection

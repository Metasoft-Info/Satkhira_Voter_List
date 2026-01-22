<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইউজার ম্যানেজমেন্ট - সাতক্ষীরা-২ ভোটার তালিকা</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Noto Sans Bengali', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-purple-800 to-indigo-900 text-white flex-shrink-0">
            <div class="p-4 border-b border-purple-700">
                <h1 class="text-xl font-bold flex items-center">
                    <i class="fas fa-vote-yea mr-2"></i>
                    সাতক্ষীরা-২
                </h1>
                <p class="text-purple-300 text-sm">অ্যাডমিন প্যানেল</p>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>ড্যাশবোর্ড</span>
                </a>
                <a href="{{ route('admin.voters') }}" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                    <i class="fas fa-users w-6"></i>
                    <span>সকল ভোটার</span>
                </a>
                <a href="{{ route('admin.upload') }}" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                    <i class="fas fa-upload w-6"></i>
                    <span>আপলোড</span>
                </a>
                <a href="{{ route('admin.banners') }}" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                    <i class="fas fa-images w-6"></i>
                    <span>ব্যানার</span>
                </a>
                <a href="{{ route('admin.breaking-news') }}" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                    <i class="fas fa-newspaper w-6"></i>
                    <span>ব্রেকিং নিউজ</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 bg-purple-700 text-white">
                    <i class="fas fa-user-cog w-6"></i>
                    <span>ইউজার ম্যানেজমেন্ট</span>
                </a>
                
                <div class="border-t border-purple-700 mt-4 pt-4">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center px-4 py-3 text-purple-200 hover:bg-purple-700 transition">
                        <i class="fas fa-external-link-alt w-6"></i>
                        <span>পাবলিক সাইট</span>
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-red-300 hover:bg-red-600 hover:text-white transition">
                            <i class="fas fa-sign-out-alt w-6"></i>
                            <span>লগআউট</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-user-cog text-purple-600 mr-2"></i>
                    ইউজার ম্যানেজমেন্ট
                </h2>
                <a href="{{ route('admin.users.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    নতুন ইউজার
                </a>
            </div>

            <div class="p-6">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Users Table -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">নাম</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">ইমেইল</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">ফোন</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">ভূমিকা</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">স্ট্যাটাস</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">তৈরির তারিখ</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-gray-500">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-purple-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-purple-600">(আপনি)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->phone ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->role === 'admin')
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-shield-alt mr-1"></i>অ্যাডমিন
                                            </span>
                                        @elseif($user->role === 'moderator')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-user-tie mr-1"></i>মডারেটর
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-user mr-1"></i>ইউজার
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active)
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>সক্রিয়
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>নিষ্ক্রিয়
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                               title="সম্পাদনা">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="p-2 {{ $user->is_active ? 'text-orange-600 hover:bg-orange-100' : 'text-green-600 hover:bg-green-100' }} rounded-lg transition"
                                                            title="{{ $user->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('আপনি কি নিশ্চিত এই ইউজার মুছে ফেলতে চান?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                            title="মুছে ফেলুন">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                        <p>কোন ইউজার পাওয়া যায়নি</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>

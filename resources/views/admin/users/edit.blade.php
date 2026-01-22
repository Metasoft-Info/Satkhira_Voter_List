<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইউজার সম্পাদনা - সাতক্ষীরা-২ ভোটার তালিকা</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Bengali', sans-serif; }
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
                    <i class="fas fa-user-edit text-purple-600 mr-2"></i>
                    ইউজার সম্পাদনা: {{ $user->name }}
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-purple-600 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    ফিরে যান
                </a>
            </div>

            <div class="p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-purple-600"></i>নাম <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                       placeholder="পুরো নাম লিখুন">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-envelope mr-2 text-purple-600"></i>ইমেইল <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                       placeholder="example@email.com">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-phone mr-2 text-purple-600"></i>ফোন
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                       placeholder="01XXXXXXXXX">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-purple-600"></i>নতুন পাসওয়ার্ড
                                    <span class="text-gray-400 text-sm">(পরিবর্তন না করলে খালি রাখুন)</span>
                                </label>
                                <input type="password" name="password"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                       placeholder="কমপক্ষে ৬ অক্ষর">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-purple-600"></i>পাসওয়ার্ড নিশ্চিত করুন
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                       placeholder="পাসওয়ার্ড আবার লিখুন">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user-tag mr-2 text-purple-600"></i>ভূমিকা <span class="text-red-500">*</span>
                                </label>
                                <select name="role" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>ইউজার</option>
                                    <option value="moderator" {{ old('role', $user->role) == 'moderator' ? 'selected' : '' }}>মডারেটর</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>অ্যাডমিন</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <p class="text-sm text-orange-600 mt-1"><i class="fas fa-info-circle mr-1"></i>আপনি নিজের ভূমিকা পরিবর্তন করতে পারবেন না</p>
                                @endif
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <label for="is_active" class="ml-2 text-gray-700">সক্রিয় অ্যাকাউন্ট</label>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="is_active" value="1">
                                @endif
                            </div>

                            <div class="flex justify-end space-x-4 pt-4 border-t">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                    বাতিল
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    আপডেট করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

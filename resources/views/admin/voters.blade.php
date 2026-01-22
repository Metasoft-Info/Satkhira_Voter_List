@extends('layouts.admin')

@section('title', 'সকল ভোটার')
@section('header', 'সকল ভোটার')

@section('content')
<div class="max-w-full mx-auto">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold">
                <i class="fas fa-users mr-2"></i>
                মোট ভোটার: {{ number_format($voters->total()) }}
            </h2>
            
            <form method="POST" action="{{ route('admin.reset.voters') }}" 
                  onsubmit="return confirm('আপনি কি নিশ্চিত সব ভোটার ডেটা মুছে ফেলতে চান? এটি পূর্বাবস্থায় ফেরানো যাবে না!');" 
                  class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <i class="fas fa-trash"></i>
                    <span>সব ডেটা মুছুন</span>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">সিরিয়াল</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">নাম</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ভোটার আইডি</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পিতার নাম</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">মাতার নাম</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">জন্ম তারিখ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">লিঙ্গ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($voters as $voter)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->serial_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $voter->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $voter->voter_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->father_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->mother_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voter->date_of_birth }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($voter->gender == 'পুরুষ')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">পুরুষ</span>
                                @else
                                    <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs">মহিলা</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                <p>কোন ভোটার পাওয়া যায়নি</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50">
            {{ $voters->links() }}
        </div>
    </div>
</div>
@endsection

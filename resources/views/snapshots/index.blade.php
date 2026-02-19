@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Create Snapshot Form -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Snapshot</h3>
            
            <form action="{{ route('snapshots.create') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Snapshot Name *</label>
                    <input type="text" name="name" id="name" placeholder="e.g., before-major-update" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="compress" value="1" checked 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Compress snapshot</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    📸 Create Snapshot
                </button>
            </form>

            <!-- Snapshot Comparison Form -->
            @if(count($snapshots) >= 2)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-md font-semibold text-gray-700 mb-3">Compare Snapshots</h4>
                <form action="{{ route('snapshots.compare') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="block text-gray-600 text-sm mb-1">First Snapshot</label>
                        <select name="snapshot1" class="w-full border rounded px-3 py-2">
                            @foreach($snapshots as $snapshot)
                            <option value="{{ $snapshot->name }}">{{ $snapshot->name }} ({{ $snapshot->createdAt->format('Y-m-d H:i') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-600 text-sm mb-1">Second Snapshot</label>
                        <select name="snapshot2" class="w-full border rounded px-3 py-2">
                            @foreach($snapshots as $snapshot)
                            <option value="{{ $snapshot->name }}">{{ $snapshot->name }} ({{ $snapshot->createdAt->format('Y-m-d H:i') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-300">
                        🔍 Compare
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h4 class="text-md font-semibold text-gray-700 mb-3">Statistics</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Snapshots:</span>
                    <span class="font-semibold">{{ count($snapshots) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Posts:</span>
                    <span class="font-semibold">{{ $posts->total() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Published Posts:</span>
                    <span class="font-semibold">{{ \App\Models\Post::where('is_published', true)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Snapshots List -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Available Snapshots</h3>
            </div>

            @if(count($snapshots) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($snapshots as $snapshot)
                <div class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $snapshot->name }}</h4>
                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                <span>📅 {{ $snapshot->createdAt->format('F j, Y H:i:s') }}</span>
                                <span>💾 {{ number_format($snapshot->size / 1024, 2) }} KB</span>
                                <span>🔌 {{ $snapshot->connectionName }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('snapshots.load', $snapshot->name) }}" method="GET" class="inline">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-3 rounded transition duration-300" 
                                        onclick="return confirm('This will overwrite your current database. Continue?')">
                                    Load
                                </button>
                            </form>
                            
                            <a href="{{ route('snapshots.download', $snapshot->name) }}" 
                               class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-3 rounded transition duration-300">
                                Download
                            </a>
                            
                            <form action="{{ route('snapshots.delete', $snapshot->name) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded transition duration-300"
                                        onclick="return confirm('Are you sure you want to delete this snapshot?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                <p class="text-lg">No snapshots available</p>
                <p class="text-sm mt-2">Create your first snapshot using the form on the left</p>
            </div>
            @endif
        </div>

        <!-- Recent Posts Preview -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Posts</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($posts as $post)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-medium text-gray-900">{{ $post->title }}</h5>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        @if($post->is_published)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">
                    No posts available
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
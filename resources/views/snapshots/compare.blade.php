@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('snapshots.index') }}" 
       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
        ← Back to Snapshots
    </a>
</div>

<h2 class="text-2xl font-bold text-gray-800 mb-6">Snapshot Comparison</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Snapshot 1 -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-600 mb-4">{{ $snapshot1->name }}</h3>
        <div class="space-y-3">
            <p><span class="font-medium">Created:</span> {{ $info1['created_at']->format('F j, Y H:i:s') }}</p>
            <p><span class="font-medium">Size:</span> {{ $info1['size'] }}</p>
            <p><span class="font-medium">Connection:</span> {{ $info1['connection'] }}</p>
            <p><span class="font-medium">Path:</span> <span class="text-sm">{{ $info1['path'] }}</span></p>
        </div>
    </div>

    <!-- Snapshot 2 -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-600 mb-4">{{ $snapshot2->name }}</h3>
        <div class="space-y-3">
            <p><span class="font-medium">Created:</span> {{ $info2['created_at']->format('F j, Y H:i:s') }}</p>
            <p><span class="font-medium">Size:</span> {{ $info2['size'] }}</p>
            <p><span class="font-medium">Connection:</span> {{ $info2['connection'] }}</p>
            <p><span class="font-medium">Path:</span> <span class="text-sm">{{ $info2['path'] }}</span></p>
        </div>
    </div>
</div>

<!-- Comparison Summary -->
<div class="mt-6 bg-white shadow-md rounded-lg p-6">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Comparison Summary</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">Time Difference</p>
            <p class="text-xl font-bold">{{ $info1['created_at']->diffForHumans($info2['created_at'], true) }}</p>
        </div>
        
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">Size Difference</p>
            <p class="text-xl font-bold">
                @php
                    $sizeDiff = $snapshot1->size - $snapshot2->size;
                    $sizeDiffFormatted = $sizeDiff > 0 ? '+' . number_format($sizeDiff / 1024, 2) : number_format($sizeDiff / 1024, 2);
                @endphp
                {{ $sizeDiffFormatted }} KB
            </p>
        </div>
        
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">Same Connection</p>
            <p class="text-xl font-bold">{{ $info1['connection'] === $info2['connection'] ? 'Yes' : 'No' }}</p>
        </div>
    </div>
</div>
@endsection
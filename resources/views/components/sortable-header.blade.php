@props(['column', 'currentSort' => null, 'currentOrder' => 'asc'])

@php
    $isActive = $currentSort === $column;
    $nextOrder = $isActive && $currentOrder === 'asc' ? 'desc' : 'asc';
    $icon = $isActive ? ($currentOrder === 'asc' ? '↑' : '↓') : '↕';
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'order' => $nextOrder]) }}" 
   class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 transition-colors duration-150">
    <span>{{ $slot }}</span>
    <span class="text-gray-400 {{ $isActive ? 'text-gray-700' : '' }}">{{ $icon }}</span>
</a>
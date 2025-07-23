<x-guest-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="py-8 text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Public Resources</h1>
                    <p class="mt-2 text-lg text-gray-600">Access educational materials and resources shared by administrators</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            @if($resources->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($resources as $resource)
                        <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-blue-50 to-indigo-100 p-8 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition-transform duration-200">
                                        <i class="fas {{ $resource->file_type_icon }} text-2xl text-indigo-600"></i>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white shadow-sm text-indigo-600">
                                        {{ strtoupper($resource->file_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $resource->title }}
                                </h3>
                                
                                @if($resource->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                        {{ $resource->description }}
                                    </p>
                                @endif
                                
                                <div class="space-y-2 text-xs text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span>{{ $resource->file_size_formatted }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $resource->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>{{ $resource->uploader->name }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('resources.public.show', $resource) }}" 
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('resources.download', $resource) }}" 
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $resources->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No resources available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new educational materials and resources.</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="mt-12 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        ← Back to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Login to Upload Resources
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-guest-layout>
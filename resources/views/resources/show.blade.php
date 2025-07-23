<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Resource Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('resources.edit', $resource) }}" 
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('resources.download', $resource) }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Download
                </a>
                <a href="{{ route('resources.public.show', $resource) }}" target="_blank"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Public Link
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Resource Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $resource->title }}</p>
                                </div>

                                @if($resource->description)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $resource->description }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">File Type</label>
                                    <div class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($resource->file_type) }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">File Size</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $resource->file_size_formatted }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        @if($resource->is_public)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Public
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Private
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Uploaded By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $resource->uploader->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Uploaded At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $resource->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">File Preview</h3>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                <div class="text-center">
                                    <i class="fas {{ $resource->file_type_icon }} text-6xl text-gray-400 mb-4"></i>
                                    <div class="text-sm font-medium text-gray-900">{{ $resource->file_name }}</div>
                                    <div class="text-sm text-gray-500 mb-4">{{ $resource->mime_type }}</div>
                                    
                                    @if($resource->file_type === 'video')
                                        <div class="mt-4">
                                            <video controls class="max-w-full h-auto mx-auto">
                                                <source src="{{ Storage::url($resource->file_path) }}" type="{{ $resource->mime_type }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @elseif($resource->file_type === 'pdf')
                                        <div class="mt-4">
                                            <embed src="{{ Storage::url($resource->file_path) }}" type="application/pdf" width="100%" height="400px" />
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('resources.download', $resource) }}" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                                        <i class="fas fa-download mr-2"></i>
                                        Download File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        @if($resource->is_public)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Public Share Link</h4>
                                <div class="flex items-center space-x-2">
                                    <input type="text" readonly 
                                        value="{{ route('resources.public.show', $resource) }}"
                                        class="flex-1 px-3 py-2 text-sm border border-blue-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onclick="this.select()">
                                    <button onclick="copyToClipboard('{{ route('resources.public.show', $resource) }}')"
                                        class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        Copy
                                    </button>
                                </div>
                                <p class="text-xs text-blue-600 mt-1">Anyone can access this link to view and download the resource</p>
                            </div>
                        @endif
                        
                        <div class="flex justify-end">
                            <a href="{{ route('resources.index') }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Back to Resources
                            </a>
                        </div>
                    </div>

                    <script>
                        function copyToClipboard(text) {
                            navigator.clipboard.writeText(text).then(function() {
                                alert('Link copied to clipboard!');
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
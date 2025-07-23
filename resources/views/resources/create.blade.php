<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload New Resource') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Resource Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Resource Type</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="resource_type" value="file" checked
                                        class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                        onchange="toggleResourceType()">
                                    <span class="text-sm text-gray-700">Upload File</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="resource_type" value="youtube"
                                        class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                        onchange="toggleResourceType()">
                                    <span class="text-sm text-gray-700">YouTube Video Link</span>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        <div id="fileUploadSection">
                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file" name="file" type="file" class="sr-only"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp4,.mov,.avi,.wmv">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, Word, Excel, PowerPoint, or Video files up to 100MB
                                    </p>
                                </div>
                            </div>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- YouTube URL Section -->
                        <div id="youtubeSection" style="display: none;">
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700">YouTube Video URL</label>
                            <div class="mt-1">
                                <input type="url" name="youtube_url" id="youtube_url" 
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-2 text-sm text-gray-500">
                                    Enter a YouTube video URL (e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ)
                                </p>
                            </div>
                            @error('youtube_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_public" value="1" checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Make this resource publicly accessible</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('resources.index') }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleResourceType() {
            const selectedType = document.querySelector('input[name="resource_type"]:checked').value;
            const fileSection = document.getElementById('fileUploadSection');
            const youtubeSection = document.getElementById('youtubeSection');
            const fileInput = document.getElementById('file');
            const youtubeInput = document.getElementById('youtube_url');
            
            if (selectedType === 'youtube') {
                fileSection.style.display = 'none';
                youtubeSection.style.display = 'block';
                fileInput.removeAttribute('required');
                youtubeInput.setAttribute('required', 'required');
            } else {
                fileSection.style.display = 'block';
                youtubeSection.style.display = 'none';
                fileInput.setAttribute('required', 'required');
                youtubeInput.removeAttribute('required');
            }
        }
    </script>
</x-app-layout>
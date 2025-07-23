<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Resources') }}
            </h2>
            <a href="{{ route('resources.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload New Resource
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        File
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Size
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Uploaded By
                                    </th>
                                    @if(true) <!-- Show views for YouTube videos -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Views
                                    </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($resources as $resource)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="fas {{ $resource->file_type_icon }} text-2xl mr-3 text-gray-600"></i>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $resource->file_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $resource->title }}</div>
                                            @if($resource->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($resource->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($resource->file_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $resource->file_size_formatted }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($resource->is_public)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Public
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Private
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $resource->uploader->name }}
                                        </td>
                                        @if(true)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($resource->is_youtube)
                                                <div class="flex items-center">
                                                    <i class="fas fa-eye mr-1 text-gray-400"></i>
                                                    {{ $resource->total_views ?? 0 }}
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('resources.show', $resource) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    View
                                                </a>
                                                <a href="{{ route('resources.edit', $resource) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    Edit
                                                </a>
                                                <a href="{{ route('resources.download', $resource) }}" class="text-green-600 hover:text-green-900" target="_blank">
                                                    Download
                                                </a>
                                                <a href="{{ route('resources.public.show', $resource) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                                    Public Link
                                                </a>
                                                <form action="{{ route('resources.destroy', $resource) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this resource?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No resources found. <a href="{{ route('resources.create') }}" class="text-blue-600 hover:text-blue-900">Upload the first resource</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $resources->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
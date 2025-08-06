<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Schools to Mentor') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                <form method="POST" action="{{ route('users.update-assigned-schools', $user) }}">
                    @csrf

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">
                            {{ __('Select the schools that this mentor will be responsible for conducting student assessment verifications.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($schools as $school)
                            <div class="flex items-start">
                                <input type="checkbox" 
                                       name="schools[]" 
                                       value="{{ $school->id }}" 
                                       id="school_{{ $school->id }}"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-1"
                                       @if(in_array($school->id, $assignedSchoolIds)) checked @endif>
                                <label for="school_{{ $school->id }}" class="ml-2 text-sm">
                                    <span class="font-medium">{{ $school->name }}</span>
                                    @if($school->province)
                                        <span class="text-gray-500 block text-xs">{{ $school->province }}</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('users.show', $user) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M12.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z"/>
                            </svg>
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            {{ __('Save School Assignments') }}
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
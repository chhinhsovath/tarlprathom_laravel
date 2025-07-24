@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ __('Translation Manager') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Manage all translations dynamically - Add, edit, or disable translations') }}</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Add Translation') }}
                </button>
                <form action="{{ route('localization.export') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        {{ __('Export to Files') }}
                    </button>
                </form>
                <a href="{{ route('localization.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
            <form method="GET" action="{{ route('localization.edit') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ $search }}" 
                        placeholder="{{ __('Search translations...') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="w-48">
                    <select name="group" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">{{ __('All Groups') }}</option>
                        @foreach($groups as $g)
                            @if($g !== 'all')
                                <option value="{{ $g }}" {{ $group === $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('localization.edit') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    {{ __('Clear') }}
                </a>
            </form>
        </div>

        <!-- Translation Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Key') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="text-blue-600">ភាសាខ្មែរ</span>
                            <span class="text-xs text-gray-400 ml-1">(Primary)</span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="text-gray-600">English</span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Group') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($translations as $translation)
                        <tr id="translation-{{ $translation->id }}" class="{{ !$translation->is_active ? 'opacity-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm text-gray-600">{{ $translation->key }}</code>
                                @if($translation->description)
                                    <span class="text-gray-400 cursor-help" title="{{ $translation->description }}">
                                        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="editable-field" data-id="{{ $translation->id }}" data-field="km">
                                    <span class="view-mode text-sm text-gray-900">{{ $translation->km ?: '-' }}</span>
                                    <input type="text" class="edit-mode hidden w-full px-2 py-1 text-sm border border-gray-300 rounded" value="{{ $translation->km }}">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="editable-field" data-id="{{ $translation->id }}" data-field="en">
                                    <span class="view-mode text-sm text-gray-900">{{ $translation->en ?: '-' }}</span>
                                    <input type="text" class="edit-mode hidden w-full px-2 py-1 text-sm border border-gray-300 rounded" value="{{ $translation->en }}">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $translation->group }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="toggleStatus({{ $translation->id }})" class="status-toggle">
                                    @if($translation->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="editDescription({{ $translation->id }}, '{{ addslashes($translation->description) }}')" 
                                    class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit Description">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteTranslation({{ $translation->id }})" 
                                    class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $translations->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Translation Modal -->
<div id="createModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('localization.create') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('Add New Translation') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Translation Key') }}</label>
                            <input type="text" name="key" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Khmer Translation') }}</label>
                            <input type="text" name="km" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('English Translation') }}</label>
                            <input type="text" name="en" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Group') }}</label>
                            <select name="group" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="general">General</option>
                                <option value="auth">Authentication</option>
                                <option value="navigation">Navigation</option>
                                <option value="students">{{ __("Students") }}</option>
                                <option value="assessments">{{ __("Assessments") }}</option>
                                <option value="schools">{{ __("Schools") }}</option>
                                <option value="mentoring">{{ __("Mentoring") }}</option>
                                <option value="levels">Levels</option>
                                <option value="subjects">Subjects</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Description') }} (Optional)</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Create') }}
                    </button>
                    <button type="button" onclick="closeCreateModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make fields editable on click
    document.querySelectorAll('.editable-field').forEach(function(field) {
        field.addEventListener('click', function() {
            const viewMode = this.querySelector('.view-mode');
            const editMode = this.querySelector('.edit-mode');
            
            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            editMode.focus();
        });
    });

    // Save on blur or enter
    document.querySelectorAll('.edit-mode').forEach(function(input) {
        input.addEventListener('blur', function() {
            saveField(this);
        });
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
    });
});

function saveField(input) {
    const field = input.closest('.editable-field');
    const id = field.dataset.id;
    const fieldName = field.dataset.field;
    const value = input.value;
    const viewMode = field.querySelector('.view-mode');
    
    // Update via AJAX
    fetch(`/localization/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            [fieldName]: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            viewMode.textContent = value || '-';
            viewMode.classList.remove('hidden');
            input.classList.add('hidden');
        }
    });
}

function toggleStatus(id) {
    fetch(`/localization/${id}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteTranslation(id) {
    if (confirm('Are you sure you want to delete this translation?')) {
        fetch(`/localization/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`translation-${id}`).remove();
            }
        });
    }
}

function editDescription(id, currentDescription) {
    const newDescription = prompt('Enter description:', currentDescription || '');
    if (newDescription !== null) {
        fetch(`/localization/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                description: newDescription
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function showCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
</script>
@endpush
@endsection
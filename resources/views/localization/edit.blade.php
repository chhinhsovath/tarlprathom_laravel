@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ __('Translation Editor') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Edit translations side by side - Khmer (Primary) and English') }}</p>
            </div>
            <a href="{{ route('localization.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back') }}
            </a>
        </div>

        <form action="{{ route('localization.update') }}" method="POST">
            @csrf
            
            <!-- Save Button (Fixed Position) -->
            <div class="sticky top-0 z-10 bg-gray-100 p-4 rounded-lg shadow-sm mb-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">{{ __('Total Keys') }}:</span> 
                        <span id="totalKeys">0</span> | 
                        <span class="font-semibold">{{ __('Translated') }}:</span> 
                        <span id="translatedKeys">0</span>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Save All Translations') }}
                    </button>
                </div>
            </div>

            <!-- Translation Files -->
            <div class="space-y-8">
                @foreach($translations as $file => $keys)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <svg class="inline w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ ucfirst($file) }}.php
                            </h3>
                        </div>
                        <div class="p-6">
                            <!-- Header Row -->
                            <div class="grid grid-cols-12 gap-4 mb-4 text-sm font-semibold text-gray-700 bg-gray-50 p-3 rounded">
                                <div class="col-span-3">{{ __('Translation Key') }}</div>
                                <div class="col-span-4">
                                    <span class="text-blue-600">ភាសាខ្មែរ (Khmer)</span>
                                    <span class="text-xs text-gray-500 ml-2">Primary</span>
                                </div>
                                <div class="col-span-4">
                                    <span class="text-gray-600">English</span>
                                    <span class="text-xs text-gray-500 ml-2">Secondary</span>
                                </div>
                                <div class="col-span-1 text-center">{{ __('Actions') }}</div>
                            </div>

                            <!-- Translation Rows -->
                            <div class="space-y-2" id="translations-{{ $file }}">
                                @foreach($keys as $key => $values)
                                    <div class="grid grid-cols-12 gap-4 items-start p-3 hover:bg-gray-50 rounded translation-row" data-key="{{ $key }}">
                                        <div class="col-span-3">
                                            <code class="text-sm text-gray-600 break-all">{{ $key }}</code>
                                        </div>
                                        <div class="col-span-4">
                                            <textarea 
                                                name="translations[{{ $file }}][{{ $key }}][km]" 
                                                class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none translation-input"
                                                rows="2"
                                                placeholder="ភាសាខ្មែរ..."
                                                data-lang="km"
                                            >{{ $values['km'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-span-4">
                                            <textarea 
                                                name="translations[{{ $file }}][{{ $key }}][en]" 
                                                class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none translation-input"
                                                rows="2"
                                                placeholder="English..."
                                                data-lang="en"
                                            >{{ $values['en'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-span-1 text-center">
                                            <button type="button" class="text-gray-400 hover:text-red-600 copy-key" title="Copy key">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add New Key -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 add-key-btn" data-file="{{ $file }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('Add New Translation Key') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Save Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Save All Translations') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    document.querySelectorAll('textarea').forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Initial resize
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    });

    // Update translation stats
    function updateStats() {
        const totalInputs = document.querySelectorAll('.translation-input').length;
        const filledInputs = document.querySelectorAll('.translation-input').length;
        let translatedCount = 0;
        
        document.querySelectorAll('.translation-row').forEach(function(row) {
            const kmInput = row.querySelector('textarea[data-lang="km"]');
            const enInput = row.querySelector('textarea[data-lang="en"]');
            if (kmInput && kmInput.value.trim() && enInput && enInput.value.trim()) {
                translatedCount++;
            }
        });
        
        document.getElementById('totalKeys').textContent = document.querySelectorAll('.translation-row').length;
        document.getElementById('translatedKeys').textContent = translatedCount;
    }

    // Initial stats update
    updateStats();

    // Update stats on input change
    document.querySelectorAll('.translation-input').forEach(function(input) {
        input.addEventListener('input', updateStats);
    });

    // Copy key to clipboard
    document.querySelectorAll('.copy-key').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const key = this.closest('.translation-row').dataset.key;
            navigator.clipboard.writeText('{{ __("' + key + '") }}').then(function() {
                // Show success feedback
                const icon = btn.querySelector('svg');
                icon.classList.add('text-green-600');
                setTimeout(function() {
                    icon.classList.remove('text-green-600');
                }, 1000);
            });
        });
    });

    // Add new key functionality
    document.querySelectorAll('.add-key-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const file = this.dataset.file;
            const container = document.getElementById('translations-' + file);
            
            // Prompt for new key
            const newKey = prompt('Enter the new translation key:');
            if (!newKey) return;
            
            // Create new row
            const newRow = document.createElement('div');
            newRow.className = 'grid grid-cols-12 gap-4 items-start p-3 hover:bg-gray-50 rounded translation-row';
            newRow.dataset.key = newKey;
            newRow.innerHTML = `
                <div class="col-span-3">
                    <code class="text-sm text-gray-600 break-all">${newKey}</code>
                </div>
                <div class="col-span-4">
                    <textarea 
                        name="translations[${file}][${newKey}][km]" 
                        class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none translation-input"
                        rows="2"
                        placeholder="ភាសាខ្មែរ..."
                        data-lang="km"
                    ></textarea>
                </div>
                <div class="col-span-4">
                    <textarea 
                        name="translations[${file}][${newKey}][en]" 
                        class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none translation-input"
                        rows="2"
                        placeholder="English..."
                        data-lang="en"
                    ></textarea>
                </div>
                <div class="col-span-1 text-center">
                    <button type="button" class="text-gray-400 hover:text-red-600 remove-key" title="Remove key">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(newRow);
            
            // Add event listeners to new elements
            newRow.querySelectorAll('textarea').forEach(function(textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                    updateStats();
                });
            });
            
            newRow.querySelector('.remove-key').addEventListener('click', function() {
                if (confirm('Remove this translation key?')) {
                    newRow.remove();
                    updateStats();
                }
            });
            
            updateStats();
        });
    });
});
</script>
@endpush
@endsection
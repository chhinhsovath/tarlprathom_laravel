@props(['mentoringVisit' => null, 'schools', 'teachers', 'action'])

@php
    $isEdit = isset($mentoringVisit) && $mentoringVisit->exists;
@endphp

<form id="mentoringForm" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    
    <!-- Include the actual form content -->
    @include('mentoring.partials.form-fields', [
        'mentoringVisit' => $mentoringVisit,
        'schools' => $schools,
        'teachers' => $teachers,
        'isEdit' => $isEdit
    ])
    
    <!-- Form Actions -->
    <div class="flex justify-end gap-4 mt-8">
        <a href="{{ route('mentoring.index') }}" 
           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
            {{ __('Cancel') }}
        </a>
        <button type="submit" 
                class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            {{ $isEdit ? __('Update Visit') : __('Save Visit') }}
        </button>
    </div>
</form>

@push('scripts')
    @include('mentoring.partials.form-scripts')
@endpush
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Language Switcher -->
        <div class="flex justify-end mb-4">
            <x-language-switcher />
        </div>
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
            <h1 class="text-3xl font-bold mb-2">{{ __('Welcome') }}, {{ $user->name }}!</h1>
            <p class="text-lg opacity-90">{{ __('Please complete your profile setup to start managing your class') }}</p>
        </div>

        <!-- Profile Setup Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">{{ __('Teacher Profile Setup') }}</h2>
                
                <form method="POST" action="{{ route('teacher.profile.update') }}" class="space-y-6">
                    @csrf
                    
                    <!-- School Selection -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Select Your School') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="school_id" name="school_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- {{ __('Choose your school') }} --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} - {{ $school->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Subject Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Subject You Teach') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="khmer" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'khmer' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Khmer') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach Khmer language') }}</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="math" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'math' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Mathematics') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach Mathematics') }}</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="both" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'both' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Both Subjects') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach both Khmer and Mathematics') }}</p>
                                </div>
                            </label>
                        </div>
                        @error('assigned_subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Grade Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Grade(s) You Teach') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="grade_4" required
                                    {{ old('holding_classes', $user->holding_classes) == 'grade_4' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Grade 4') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach Grade 4 students') }}</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="grade_5" required
                                    {{ old('holding_classes', $user->holding_classes) == 'grade_5' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Grade 5') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach Grade 5 students') }}</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="both" required
                                    {{ old('holding_classes', $user->holding_classes) == 'both' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">{{ __('Both Grades') }}</span>
                                    <p class="text-sm text-gray-500">{{ __('I teach both Grade 4 and Grade 5') }}</p>
                                </div>
                            </label>
                        </div>
                        @error('holding_classes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Phone Number') }} ({{ __('Optional') }})
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="012 345 678"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="px-4 py-2 text-gray-600 hover:text-gray-900">
                            {{ __('Logout') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Save and Continue') }} →
                        </button>
                    </div>
                </form>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
        
        <!-- Help Text -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold">{{ __('Why do we need this information?') }}</p>
                    <p>{{ __('This helps us organize students by school, grade, and subject for accurate assessment tracking.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus first empty field
    const firstEmptyField = document.querySelector('select:not([value]):not([value=""]), input[type="radio"]:not(:checked)');
    if (firstEmptyField) {
        firstEmptyField.focus();
    }
});
</script>
@endsection
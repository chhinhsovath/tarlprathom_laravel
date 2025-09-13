@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
            <h1 class="text-3xl font-bold mb-2">សូមស្វាគមន៍, {{ $user->name }}!</h1>
            <p class="text-lg opacity-90">សូមបំពេញប្រវត្តិរូបរបស់អ្នកដើម្បីចាប់ផ្តើមគ្រប់គ្រងថ្នាក់រៀនរបស់អ្នក</p>
        </div>

        <!-- Profile Setup Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">ការកំណត់ប្រវត្តិរូបគ្រូបង្រៀន</h2>
                
                <form method="POST" action="{{ route('teacher.profile.update') }}" class="space-y-6">
                    @csrf
                    
                    <!-- School Selection -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                            ជ្រើសរើសសាលារបស់អ្នក <span class="text-red-500">*</span>
                        </label>
                        <select id="school_id" name="school_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- ជ្រើសរើសសាលារបស់អ្នក --</option>
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
                            មុខវិជ្ជាដែលអ្នកបង្រៀន <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="khmer" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'khmer' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">ភាសាខ្មែរ</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនភាសាខ្មែរ</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="math" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'math' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">គណិតវិទ្យា</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនគណិតវិទ្យា</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="assigned_subject" value="both" required
                                    {{ old('assigned_subject', $user->assigned_subject) == 'both' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">មុខវិជ្ជាទាំងពីរ</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនទាំងភាសាខ្មែរ និងគណិតវិទ្យា</p>
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
                            ថ្នាក់ដែលអ្នកបង្រៀន <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="grade_4" required
                                    {{ old('holding_classes', $user->holding_classes) == 'grade_4' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">ថ្នាក់ទី ៤</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនសិស្សថ្នាក់ទី ៤</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="grade_5" required
                                    {{ old('holding_classes', $user->holding_classes) == 'grade_5' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">ថ្នាក់ទី ៥</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនសិស្សថ្នាក់ទី ៥</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="holding_classes" value="both" required
                                    {{ old('holding_classes', $user->holding_classes) == 'both' ? 'checked' : '' }}
                                    class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium">ថ្នាក់ទាំងពីរ</span>
                                    <p class="text-sm text-gray-500">ខ្ញុំបង្រៀនទាំងថ្នាក់ទី ៤ និងថ្នាក់ទី ៥</p>
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
                            លេខទូរស័ព្ទ (ស្រេចចិត្ត)
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
                            ចាកចេញ
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            រក្សាទុក និងបន្ត →
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
                    <p class="font-semibold">ហេតុអ្វីបានជាយើងត្រូវការព័ត៌មាននេះ?</p>
                    <p>វាជួយយើងក្នុងការរៀបចំសិស្សតាមសាលា ថ្នាក់ និងមុខវិជ្ជាសម្រាប់ការតាមដានការវាយតម្លៃដែលត្រឹមត្រូវ។</p>
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
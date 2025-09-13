@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Manage Students') }}</h1>
                    <p class="text-gray-600 mt-1">
                        {{ __('School') }}: <span class="font-medium">{{ $user->school->name ?? 'N/A' }}</span> | 
                        {{ __('Grade(s)') }}: <span class="font-medium">
                            @if($user->holding_classes == 'grade_4') Grade 4
                            @elseif($user->holding_classes == 'grade_5') Grade 5
                            @else Grade 4 & 5
                            @endif
                        </span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openQuickAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('Add Student') }}
                    </button>
                    <button onclick="openBulkAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Bulk Add') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Student Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach($grades as $grade)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Grade') }} {{ $grade }}</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $students->where('class', $grade)->count() }}
                        </p>
                        <p class="text-xs text-gray-500">{{ __('Students') }}</p>
                    </div>
                    <div class="text-4xl text-indigo-200">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">{{ __('Total Students') }}</p>
                        <p class="text-3xl font-bold">{{ $students->count() }}</p>
                        <p class="text-xs opacity-75">{{ __('In your classes') }}</p>
                    </div>
                    <div class="text-white opacity-50">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student List -->
        @foreach($grades as $grade)
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Grade') }} {{ $grade }} {{ __('Students') }}</h2>
            </div>
            
            @php $gradeStudents = $students->where('class', $grade) @endphp
            
            @if($gradeStudents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('#') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Gender') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Age') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Assessment Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($gradeStudents as $index => $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $student->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ ucfirst($student->gender) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->age }} years
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Baseline: Not Started</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('assessments.create') }}?student_id={{ $student->id }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Assess') }}
                                </a>
                                <button onclick="editStudent({{ $student->id }})" 
                                   class="text-gray-600 hover:text-gray-900">
                                    {{ __('Edit') }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="mt-4 text-gray-600">{{ __('No students added yet for Grade') }} {{ $grade }}</p>
                <button onclick="openQuickAddModal({{ $grade }})" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    {{ __('Add First Student') }}
                </button>
            </div>
            @endif
        </div>
        @endforeach

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                ← {{ __('Back to Dashboard') }}
            </a>
            @if($students->count() > 0)
            <a href="{{ route('assessments.select-students') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                {{ __('Start Assessment') }} →
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Quick Add Student Modal -->
<div id="quickAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Add New Student') }}</h3>
        <form method="POST" action="{{ route('teacher.students.quick-add') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Student Name') }}</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Grade') }}</label>
                    <select name="grade" id="modalGrade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($grades as $grade)
                        <option value="{{ $grade }}">Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Gender') }}</label>
                    <select name="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Age') }}</label>
                    <input type="number" name="age" min="8" max="18" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeQuickAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    {{ __('Add Student') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Add Students Modal -->
<div id="bulkAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Bulk Add Students') }}</h3>
        <form method="POST" action="{{ route('teacher.students.bulk-add') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Grade') }}</label>
                    <select name="grade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($grades as $grade)
                        <option value="{{ $grade }}">Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Student List') }}</label>
                    <p class="text-xs text-gray-500 mb-2">{{ __('Enter one student per line in format: Name, Gender(M/F), Age') }}</p>
                    <textarea name="students" rows="10" required 
                        placeholder="John Doe, M, 10&#10;Jane Smith, F, 11&#10;..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"></textarea>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded p-3">
                    <p class="text-sm text-blue-800">
                        <strong>{{ __('Example:') }}</strong><br>
                        Sok Dara, M, 10<br>
                        Chan Sreynich, F, 11<br>
                        Keo Sokha, M, 10
                    </p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeBulkAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Add All Students') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openQuickAddModal(grade = null) {
    document.getElementById('quickAddModal').classList.remove('hidden');
    if (grade) {
        document.getElementById('modalGrade').value = grade;
    }
}

function closeQuickAddModal() {
    document.getElementById('quickAddModal').classList.add('hidden');
}

function openBulkAddModal() {
    document.getElementById('bulkAddModal').classList.remove('hidden');
}

function closeBulkAddModal() {
    document.getElementById('bulkAddModal').classList.add('hidden');
}

function editStudent(id) {
    // Implement edit functionality
    window.location.href = `/students/${id}/edit`;
}
</script>
@endsection
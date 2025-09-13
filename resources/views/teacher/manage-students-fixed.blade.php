@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Students</h1>
                    <p class="text-gray-600 mt-1">
                        School: <span class="font-medium">{{ optional($user->school)->name ?? 'N/A' }}</span> | 
                        Grade(s): <span class="font-medium">
                            @if($user->holding_classes == 'grade_4') 
                                Grade 4
                            @elseif($user->holding_classes == 'grade_5') 
                                Grade 5
                            @else 
                                Grade 4 & 5
                            @endif
                        </span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openQuickAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Add Student
                    </button>
                    <button onclick="openBulkAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Bulk Add
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
                        <p class="text-sm text-gray-600">Grade {{ $grade }}</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $students->where('class', $grade)->count() }}
                        </p>
                        <p class="text-xs text-gray-500">Students</p>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Students</p>
                        <p class="text-3xl font-bold">{{ $students->count() }}</p>
                        <p class="text-xs opacity-75">In your classes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student List -->
        @foreach($grades as $grade)
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Grade {{ $grade }} Students</h2>
            </div>
            
            @php 
                $gradeStudents = $students->where('class', $grade);
            @endphp
            
            @if($gradeStudents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $counter = 1; @endphp
                        @foreach($gradeStudents as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $counter++ }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $student->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ ucfirst($student->gender ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->age ?? 'N/A' }} years
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('assessments.create', ['student_id' => $student->id]) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Assess
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" 
                                   class="text-gray-600 hover:text-gray-900">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center">
                <p class="mt-4 text-gray-600">No students added yet for Grade {{ $grade }}</p>
                <button onclick="openQuickAddModal({{ $grade }})" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Add First Student
                </button>
            </div>
            @endif
        </div>
        @endforeach

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Dashboard
            </a>
            @if($students->count() > 0)
            <a href="{{ route('assessments.index') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Start Assessment →
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Quick Add Student Modal -->
<div id="quickAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Student</h3>
        <form method="POST" action="{{ route('teacher.students.quick-add') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Student Name</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Grade</label>
                    <select name="grade" id="modalGrade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach($grades as $grade)
                        <option value="{{ $grade }}">Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" name="age" min="8" max="18" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeQuickAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Add Student
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Add Students Modal -->
<div id="bulkAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Bulk Add Students</h3>
        <form method="POST" action="{{ route('teacher.students.bulk-add') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Grade</label>
                    <select name="grade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach($grades as $grade)
                        <option value="{{ $grade }}">Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student List</label>
                    <p class="text-xs text-gray-500 mb-2">Enter one student per line in format: Name, Gender(M/F), Age</p>
                    <textarea name="students" rows="10" required 
                        placeholder="John Doe, M, 10&#10;Jane Smith, F, 11&#10;..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono text-sm"></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeBulkAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Add All Students
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
</script>
@endsection
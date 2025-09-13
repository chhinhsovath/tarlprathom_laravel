@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Manage Students - Debug Version</h1>
            
            <div class="mb-4">
                <p><strong>User:</strong> {{ $user->name }}</p>
                <p><strong>School ID:</strong> {{ $user->school_id }}</p>
                <p><strong>Holding Classes:</strong> {{ $user->holding_classes }}</p>
                <p><strong>Grades (PHP):</strong> 
                    @php
                        echo implode(', ', $grades);
                    @endphp
                </p>
            </div>
            
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2">Students Count: {{ $students->count() }}</h2>
                
                @foreach($grades as $grade)
                    <div class="mb-2">
                        <p>Grade {{ $grade }}: {{ $students->where('class', $grade)->count() }} students</p>
                    </div>
                @endforeach
            </div>
            
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2">Student List:</h2>
                @if($students->count() > 0)
                    <ul>
                        @foreach($students as $student)
                            <li>{{ $student->name }} - Class: {{ $student->class }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No students found.</p>
                @endif
            </div>
            
            <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@include('components.translations')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Language Test Page</h2>
            
            <div class="space-y-2">
                <p><strong>Current Locale:</strong> {{ app()->getLocale() }}</p>
                <p><strong>Session Locale:</strong> {{ session('locale', 'not set') }}</p>
                <p><strong>Config Locale:</strong> {{ config('app.locale') }}</p>
            </div>
            
            <div class="mt-6 p-4 bg-gray-100 rounded">
                <h3 class="font-bold mb-2">Translation Tests:</h3>
                <ul class="space-y-1">
                    <li>Welcome = {{ trans_km('Welcome') }}</li>
                    <li>Teacher Profile Setup = {{ trans_km('Teacher Profile Setup') }}</li>
                    <li>Select Your School = {{ trans_km('Select Your School') }}</li>
                    <li>Subject You Teach = {{ trans_km('Subject You Teach') }}</li>
                    <li>Khmer = {{ trans_km('Khmer') }}</li>
                    <li>Mathematics = {{ trans_km('Mathematics') }}</li>
                    <li>Grade 4 = {{ trans_km('Grade 4') }}</li>
                    <li>Grade 5 = {{ trans_km('Grade 5') }}</li>
                    <li>Save and Continue = {{ trans_km('Save and Continue') }}</li>
                </ul>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('language.switch', 'km') }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Set to Khmer</a>
                <a href="{{ route('language.switch', 'en') }}" class="bg-green-500 text-white px-4 py-2 rounded">Set to English</a>
            </div>
        </div>
    </div>
</div>
@endsection
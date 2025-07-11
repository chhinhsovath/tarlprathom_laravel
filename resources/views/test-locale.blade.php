<!DOCTYPE html>
<html>
<head>
    <title>Locale Test</title>
</head>
<body>
    <h1>Locale Debug Information</h1>
    
    <p><strong>Current Locale:</strong> {{ app()->getLocale() }}</p>
    <p><strong>Config Locale:</strong> {{ config('app.locale') }}</p>
    <p><strong>Session Locale:</strong> {{ session('locale', 'not set') }}</p>
    <p><strong>Cookie Locale:</strong> {{ request()->cookie('locale', 'not set') }}</p>
    
    <h2>Translation Test</h2>
    <p><strong>__('Assessments'):</strong> {{ __('Assessments') }}</p>
    <p><strong>__('Students'):</strong> {{ __('Students') }}</p>
    <p><strong>__('Dashboard'):</strong> {{ __('Dashboard') }}</p>
    <p><strong>trans('Assessments'):</strong> {{ trans('Assessments') }}</p>
    <p><strong>Lang::get('Assessments'):</strong> {{ Lang::get('Assessments') }}</p>
    
    <h2>Language Files</h2>
    <p><strong>km.json exists (lang/):</strong> {{ file_exists(base_path('lang/km.json')) ? 'Yes' : 'No' }}</p>
    <p><strong>km.json exists (resources/lang/):</strong> {{ file_exists(resource_path('lang/km.json')) ? 'Yes' : 'No' }}</p>
    <p><strong>Lang path:</strong> {{ app()->langPath() }}</p>
    <p><strong>Resource path:</strong> {{ resource_path('lang') }}</p>
    
    <h2>JSON Translation Test</h2>
    <?php
        $translations = json_decode(file_get_contents(resource_path('lang/km.json')), true);
    ?>
    <p><strong>Loaded translations count:</strong> {{ count($translations) }}</p>
    <p><strong>Sample: Assessments in km.json:</strong> {{ $translations['Assessments'] ?? 'NOT FOUND' }}</p>
    
    <h2>Actions</h2>
    <p>
        <a href="{{ url('/language/en') }}" style="margin-right: 10px;">Switch to English</a>
        <a href="{{ url('/language/km') }}">Switch to Khmer</a>
    </p>
</body>
</html>
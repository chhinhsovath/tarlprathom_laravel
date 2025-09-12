<!DOCTYPE html>
<html>
<head>
    <title>Assessment Debug</title>
</head>
<body>
    <h1>Debug Assessment Variables</h1>
    
    <p>Subject: {{ var_export($subject, true) }}</p>
    <p>Cycle: {{ var_export($cycle, true) }}</p>
    <p>Students count: {{ count($students) }}</p>
    <p>School: {{ var_export($school, true) }}</p>
    
    @if($school)
        <p>School dates:</p>
        <ul>
            <li>Baseline start: {{ var_export($school->baseline_start_date, true) }}</li>
            <li>Baseline end: {{ var_export($school->baseline_end_date, true) }}</li>
            <li>Midline start: {{ var_export($school->midline_start_date, true) }}</li>
            <li>Midline end: {{ var_export($school->midline_end_date, true) }}</li>
        </ul>
    @endif
    
    <p>Period Status: {{ var_export($periodStatus, true) }}</p>
</body>
</html>
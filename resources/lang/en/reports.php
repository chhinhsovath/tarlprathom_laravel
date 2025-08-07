<?php

return [
    // Main Headers and Navigation
    'Reports' => 'Reports',
    'Available Reports' => 'Available Reports',
    'Generate Report' => 'Generate Report',
    'Export Data' => 'Export Data',
    'Recent Assessments' => 'Recent Assessments',
    'View Reports' => 'View Reports',
    
    // Statistics Labels
    'Total Students' => 'Total Students',
    'Total Assessments' => 'Total Assessments',
    'Total Schools' => 'Total Schools',
    'Mentoring Visits' => 'Mentoring Visits',
    'My Total Visits' => 'My Total Visits',
    'Schools Visited' => 'Schools Visited',
    'Teachers Mentored' => 'Teachers Mentored',
    
    // Report Types
    'Student Performance' => 'Student Performance',
    'Student Performance Report' => 'Student Performance Report',
    'My Students Performance' => 'My Students Performance',
    'Track individual student performance across assessments' => 'Track individual student performance across assessments',
    'Comprehensive analysis using Assessment Dataset (student_id, cycle, subject, level, score). Formula: Performance Distribution = COUNT(level) GROUP BY subject, cycle. Expected Result: Visual breakdown of student performance levels across Khmer (Beginner→Comp.2) and Math (Beginner→Word Problem) subjects by assessment cycles.' => 'Comprehensive analysis using Assessment Dataset (student_id, cycle, subject, level, score). Formula: Performance Distribution = COUNT(level) GROUP BY subject, cycle. Expected Result: Visual breakdown of student performance levels across Khmer (Beginner→Comp.2) and Math (Beginner→Word Problem) subjects by assessment cycles.',
    'School-specific assessment analysis filtered by teacher\'s school_id. Formula: School Performance = COUNT(level) WHERE school_id = teacher.school_id GROUP BY subject, cycle, class. Expected Result: Detailed breakdown of student performance levels within your school, showing distribution across classes and subjects for targeted teaching interventions.' => 'School-specific assessment analysis filtered by teacher\'s school_id. Formula: School Performance = COUNT(level) WHERE school_id = teacher.school_id GROUP BY subject, cycle, class. Expected Result: Detailed breakdown of student performance levels within your school, showing distribution across classes and subjects for targeted teaching interventions.',
    'Assessment analysis filtered by mentor\'s assigned schools. Formula: Mentor Performance = COUNT(level) WHERE school_id IN (mentor.assigned_schools) GROUP BY school, subject, cycle. Expected Result: Performance overview across your assigned schools, enabling targeted mentoring focus on schools with lower performance levels.' => 'Assessment analysis filtered by mentor\'s assigned schools. Formula: Mentor Performance = COUNT(level) WHERE school_id IN (mentor.assigned_schools) GROUP BY school, subject, cycle. Expected Result: Performance overview across your assigned schools, enabling targeted mentoring focus on schools with lower performance levels.',
    
    'School Comparison' => 'School Comparison',
    'School Comparison Report' => 'School Comparison Report',
    'Compare performance across different schools' => 'Compare performance across different schools',
    'Cross-school performance analysis using aggregated Assessment data. Formula: School Performance = AVG(score) + COUNT(level) WHERE school_id GROUP BY school, subject, cycle. Expected Result: Comparative charts showing average scores and level distribution across schools, enabling identification of high-performing institutions.' => 'Cross-school performance analysis using aggregated Assessment data. Formula: School Performance = AVG(score) + COUNT(level) WHERE school_id GROUP BY school, subject, cycle. Expected Result: Comparative charts showing average scores and level distribution across schools, enabling identification of high-performing institutions.',
    
    'Progress Tracking' => 'Progress Tracking',
    'Progress Tracking Report' => 'Progress Tracking Report',
    'Monitor student progress over time' => 'Monitor student progress over time',
    'Longitudinal student progress analysis across assessment cycles. Formula: Progress = (Latest_Level_Index - Baseline_Level_Index) + (Latest_Score - Baseline_Score). Expected Result: Individual student progression trajectories showing improvements from baseline through midline to endline assessments.' => 'Longitudinal student progress analysis across assessment cycles. Formula: Progress = (Latest_Level_Index - Baseline_Level_Index) + (Latest_Score - Baseline_Score). Expected Result: Individual student progression trajectories showing improvements from baseline through midline to endline assessments.',
    
    'Mentoring Impact' => 'Mentoring Impact',
    'Mentoring Impact Report' => 'Mentoring Impact Report',
    'Analyze the impact of mentoring on student performance' => 'Analyze the impact of mentoring on student performance',
    'Correlation analysis between Mentoring Visits and Student Performance datasets. Formula: Impact Score = ΔAVG(assessment_score) / COUNT(mentoring_visits) WHERE visit_date BETWEEN baseline_date AND endline_date. Expected Result: Statistical evidence of mentoring effectiveness on student learning outcomes.' => 'Correlation analysis between Mentoring Visits and Student Performance datasets. Formula: Impact Score = ΔAVG(assessment_score) / COUNT(mentoring_visits) WHERE visit_date BETWEEN baseline_date AND endline_date. Expected Result: Statistical evidence of mentoring effectiveness on student learning outcomes.',
    
    'My Mentoring' => 'My Mentoring',
    'My Mentoring Report' => 'My Mentoring Report',
    'View your personal mentoring activities' => 'View your personal mentoring activities',
    'Personal mentoring record using Mentoring Visits dataset. Formula: My Impact = COUNT(visits) WHERE mentor_id = current_user GROUP BY school, month. Expected Result: Personal dashboard showing visit frequency, schools covered, and feedback summaries, helping mentors track their contribution to teaching quality improvement.' => 'Personal mentoring record using Mentoring Visits dataset. Formula: My Impact = COUNT(visits) WHERE mentor_id = current_user GROUP BY school, month. Expected Result: Personal dashboard showing visit frequency, schools covered, and feedback summaries, helping mentors track their contribution to teaching quality improvement.',
    
    'Performance Calculation' => 'Performance Calculation',
    'Performance Calculation Report' => 'Performance Calculation Report',
    'Calculate and analyze performance metrics' => 'Calculate and analyze performance metrics',
    'TaRL methodology performance indicators using level aggregation. Formula: Language_Readers_% = (Para+Story+Comp1+Comp2)/Total_Students×100, Math_Operations_% = (Subtraction+Division+WordProblem)/Total_Students×100. Expected Result: Standardized performance percentages enabling comparison with national TaRL benchmarks.' => 'TaRL methodology performance indicators using level aggregation. Formula: Language_Readers_% = (Para+Story+Comp1+Comp2)/Total_Students×100, Math_Operations_% = (Subtraction+Division+WordProblem)/Total_Students×100. Expected Result: Standardized performance percentages enabling comparison with national TaRL benchmarks.',
    
    'Class Progress Report' => 'Class Progress Report',
    'Class-level longitudinal analysis using Student-Assessment relationships. Formula: Class Progress = Σ(student_progress) / COUNT(students) WHERE class_id GROUP BY assessment_cycle. Expected Result: Class-wise progress trajectories showing average improvements, helping identify which classes need additional support.' => 'Class-level longitudinal analysis using Student-Assessment relationships. Formula: Class Progress = Σ(student_progress) / COUNT(students) WHERE class_id GROUP BY assessment_cycle. Expected Result: Class-wise progress trajectories showing average improvements, helping identify which classes need additional support.',
    
    'Class Performance' => 'Class Performance',
    'Class Performance Report' => 'Class Performance Report',
    'View performance by class level' => 'View performance by class level',
    
    // Export Options
    'Export Assessments (XLSX)' => 'Export Assessments (XLSX)',
    'Export Mentoring Visits (XLSX)' => 'Export Mentoring Visits (XLSX)',
    'Export to Excel' => 'Export to Excel',
    'Export to PDF' => 'Export to PDF',
    'Download Report' => 'Download Report',
    'Print Report' => 'Print Report',
    
    // Table Headers
    'Date' => 'Date',
    'Student' => 'Student',
    'School' => 'School',
    'Teacher' => 'Teacher',
    'Subject' => 'Subject',
    'Level' => 'Level',
    'Score' => 'Score',
    'Grade' => 'Grade',
    'Gender' => 'Gender',
    'Province' => 'Province',
    'District' => 'District',
    'Cluster' => 'Cluster',
    'Cycle' => 'Cycle',
    'Status' => 'Status',
    
    // Subjects
    'Khmer' => 'Khmer',
    'Math' => 'Math',
    'khmer' => 'Khmer',
    'math' => 'Math',
    
    // Assessment Levels - Khmer
    'Letter' => 'Letter',
    'Syllable' => 'Syllable',
    'Word' => 'Word',
    'Paragraph' => 'Paragraph',
    'Story' => 'Story',
    'Comp. 1' => 'Comp. 1',
    'Comp. 2' => 'Comp. 2',
    
    // Assessment Levels - Math
    'Number Recognition' => 'Number Recognition',
    '1-Digit' => '1-Digit',
    '2-Digit' => '2-Digit',
    'Addition' => 'Addition',
    'Subtraction' => 'Subtraction',
    'Multiplication' => 'Multiplication',
    'Division' => 'Division',
    'Word Problem' => 'Word Problem',
    
    // Assessment Cycles
    'Baseline' => 'Baseline',
    'Midline' => 'Midline',
    'Endline' => 'Endline',
    'baseline' => 'Baseline',
    'midline' => 'Midline',
    'endline' => 'Endline',
    
    // Actions
    'View' => 'View',
    'View Details' => 'View Details',
    'View Report' => 'View Report',
    'View assessment history' => 'View assessment history',
    'Generate' => 'Generate',
    'Export' => 'Export',
    'Print' => 'Print',
    'Download' => 'Download',
    'Back' => 'Back',
    'Back to Reports' => 'Back to Reports',
    
    // Messages
    'No data available' => 'No data available',
    'No reports found' => 'No reports found',
    'Report generated successfully' => 'Report generated successfully',
    'Export completed successfully' => 'Export completed successfully',
    'Loading report...' => 'Loading report...',
    'Generating report...' => 'Generating report...',
    'Please select filters' => 'Please select filters',
    'Please select a date range' => 'Please select a date range',
];
<?php

return [
    // Main Headers and Navigation
    'Reports' => 'របាយការណ៍',
    'Available Reports' => 'របាយការណ៍ដែលមាន',
    'Generate Report' => 'បង្កើតរបាយការណ៍',
    'Export Data' => 'នាំចេញទិន្នន័យ',
    'Recent Assessments' => 'ការវាយតម្លៃថ្មីៗ',
    'View Reports' => 'មើលរបាយការណ៍',
    
    // Statistics Labels
    'Total Students' => 'សិស្សសរុប',
    'Total Assessments' => 'ការវាយតម្លៃសរុប',
    'Total Schools' => 'សាលាសរុប',
    'Mentoring Visits' => 'ការទស្សនកិច្ចណែនាំ',
    'My Total Visits' => 'ការទស្សនកិច្ចសរុបរបស់ខ្ញុំ',
    'Schools Visited' => 'សាលាដែលបានទស្សនកិច្ច',
    'Teachers Mentored' => 'គ្រូដែលបានណែនាំ',
    
    // Report Types
    'Student Performance' => 'លទ្ធផលសិស្ស',
    'Student Performance Report' => 'របាយការណ៍លទ្ធផលសិស្ស',
    'My Students Performance' => 'លទ្ធផលសិស្សរបស់ខ្ញុំ',
    'Track individual student performance across assessments' => 'តាមដានលទ្ធផលសិស្សម្នាក់ៗតាមរយៈការវាយតម្លៃ',
    'Comprehensive analysis using Assessment Dataset (student_id, cycle, subject, level, score). Formula: Performance Distribution = COUNT(level) GROUP BY subject, cycle. Expected Result: Visual breakdown of student performance levels across Khmer (Beginner→Comp.2) and Math (Beginner→Word Problem) subjects by assessment cycles.' => 'ការវិភាគលម្អិតលើលទ្ធផលសិស្សតាមមុខវិជ្ជា និងវដ្តនៃការវាយតម្លៃ',
    'School-specific assessment analysis filtered by teacher\'s school_id. Formula: School Performance = COUNT(level) WHERE school_id = teacher.school_id GROUP BY subject, cycle, class. Expected Result: Detailed breakdown of student performance levels within your school, showing distribution across classes and subjects for targeted teaching interventions.' => 'ការវិភាគលទ្ធផលសិស្សក្នុងសាលារបស់អ្នកតាមថ្នាក់ និងមុខវិជ្ជា',
    'Assessment analysis filtered by mentor\'s assigned schools. Formula: Mentor Performance = COUNT(level) WHERE school_id IN (mentor.assigned_schools) GROUP BY school, subject, cycle. Expected Result: Performance overview across your assigned schools, enabling targeted mentoring focus on schools with lower performance levels.' => 'ការវិភាគលទ្ធផលសិស្សនៅសាលាដែលអ្នកទទួលខុសត្រូវ',
    
    'School Comparison' => 'ប្រៀបធៀបសាលា',
    'School Comparison Report' => 'របាយការណ៍ប្រៀបធៀបសាលា',
    'Compare performance across different schools' => 'ប្រៀបធៀបលទ្ធផលរវាងសាលាផ្សេងៗ',
    'Cross-school performance analysis using aggregated Assessment data. Formula: School Performance = AVG(score) + COUNT(level) WHERE school_id GROUP BY school, subject, cycle. Expected Result: Comparative charts showing average scores and level distribution across schools, enabling identification of high-performing institutions.' => 'ការវិភាគប្រៀបធៀបលទ្ធផលរវាងសាលាផ្សេងៗ តាមមុខវិជ្ជា និងវដ្តនៃការវាយតម្លៃ',
    
    'Progress Tracking' => 'តាមដានវឌ្ឍនភាព',
    'Progress Tracking Report' => 'របាយការណ៍តាមដានវឌ្ឍនភាព',
    'Monitor student progress over time' => 'តាមដានវឌ្ឍនភាពសិស្សតាមពេលវេលា',
    'Longitudinal student progress analysis across assessment cycles. Formula: Progress = (Latest_Level_Index - Baseline_Level_Index) + (Latest_Score - Baseline_Score). Expected Result: Individual student progression trajectories showing improvements from baseline through midline to endline assessments.' => 'ការវិភាគវឌ្ឍនភាពសិស្សម្នាក់ៗពីការវាយតម្លៃមូលដ្ឋាន ពាក់កណ្តាល ដល់ចុងក្រោយ',
    
    'Mentoring Impact' => 'ផលប៉ះពាល់នៃការណែនាំ',
    'Mentoring Impact Report' => 'របាយការណ៍ផលប៉ះពាល់នៃការណែនាំ',
    'Analyze the impact of mentoring on student performance' => 'វិភាគផលប៉ះពាល់នៃការណែនាំលើលទ្ធផលសិស្ស',
    'Correlation analysis between Mentoring Visits and Student Performance datasets. Formula: Impact Score = ΔAVG(assessment_score) / COUNT(mentoring_visits) WHERE visit_date BETWEEN baseline_date AND endline_date. Expected Result: Statistical evidence of mentoring effectiveness on student learning outcomes.' => 'ការវិភាគទំនាក់ទំនងរវាងការទស្សនកិច្ចណែនាំ និងលទ្ធផលសិក្សារបស់សិស្ស',
    
    'My Mentoring' => 'ការណែនាំរបស់ខ្ញុំ',
    'My Mentoring Report' => 'របាយការណ៍ការណែនាំរបស់ខ្ញុំ',
    'View your personal mentoring activities' => 'មើលសកម្មភាពការណែនាំផ្ទាល់ខ្លួនរបស់អ្នក',
    'Personal mentoring record using Mentoring Visits dataset. Formula: My Impact = COUNT(visits) WHERE mentor_id = current_user GROUP BY school, month. Expected Result: Personal dashboard showing visit frequency, schools covered, and feedback summaries, helping mentors track their contribution to teaching quality improvement.' => 'កំណត់ត្រាការទស្សនកិច្ចណែនាំផ្ទាល់ខ្លួន និងការរួមចំណែកក្នុងការលើកកម្ពស់គុណភាពបង្រៀន',
    
    'Performance Calculation' => 'ការគណនាលទ្ធផល',
    'Performance Calculation Report' => 'របាយការណ៍ការគណនាលទ្ធផល',
    'Calculate and analyze performance metrics' => 'គណនា និងវិភាគរង្វាស់លទ្ធផល',
    'TaRL methodology performance indicators using level aggregation. Formula: Language_Readers_% = (Para+Story+Comp1+Comp2)/Total_Students×100, Math_Operations_% = (Subtraction+Division+WordProblem)/Total_Students×100. Expected Result: Standardized performance percentages enabling comparison with national TaRL benchmarks.' => 'ការគណនាសូចនាករលទ្ធផលតាមវិធីសាស្ត្រ TaRL សម្រាប់ការប្រៀបធៀបជាមួយស្តង់ដារជាតិ',
    
    'Class Progress Report' => 'របាយការណ៍វឌ្ឍនភាពថ្នាក់',
    'Class-level longitudinal analysis using Student-Assessment relationships. Formula: Class Progress = Σ(student_progress) / COUNT(students) WHERE class_id GROUP BY assessment_cycle. Expected Result: Class-wise progress trajectories showing average improvements, helping identify which classes need additional support.' => 'ការវិភាគវឌ្ឍនភាពតាមថ្នាក់រៀន ដើម្បីកំណត់ថ្នាក់ដែលត្រូវការជំនួយបន្ថែម',
    
    'Class Performance' => 'លទ្ធផលថ្នាក់',
    'Class Performance Report' => 'របាយការណ៍លទ្ធផលថ្នាក់',
    'View performance by class level' => 'មើលលទ្ធផលតាមកម្រិតថ្នាក់',
    
    // Export Options
    'Export Assessments (XLSX)' => 'នាំចេញការវាយតម្លៃ (XLSX)',
    'Export Mentoring Visits (XLSX)' => 'នាំចេញការទស្សនកិច្ចណែនាំ (XLSX)',
    'Export to Excel' => 'នាំចេញទៅ Excel',
    'Export to PDF' => 'នាំចេញទៅ PDF',
    'Download Report' => 'ទាញយករបាយការណ៍',
    'Print Report' => 'បោះពុម្ពរបាយការណ៍',
    
    // Table Headers
    'Date' => 'កាលបរិច្ឆេទ',
    'Student' => 'សិស្ស',
    'School' => 'សាលា',
    'Teacher' => 'គ្រូ',
    'Subject' => 'មុខវិជ្ជា',
    'Level' => 'កម្រិត',
    'Score' => 'ពិន្ទុ',
    'Grade' => 'ថ្នាក់',
    'Gender' => 'ភេទ',
    'Province' => 'ខេត្ត',
    'District' => 'ស្រុក/ក្រុង',
    'Cluster' => 'ក្រុម',
    'Cycle' => 'វដ្ត',
    'Status' => 'ស្ថានភាព',
    
    // Subjects
    'Khmer' => 'ភាសាខ្មែរ',
    'Math' => 'គណិតវិទ្យា',
    'khmer' => 'ភាសាខ្មែរ',
    'math' => 'គណិតវិទ្យា',
    
    // Assessment Levels - Khmer
    'Letter' => 'អក្សរ',
    'Syllable' => 'ព្យាង្គ',
    'Word' => 'ពាក្យ',
    'Paragraph' => 'កថាខណ្ឌ',
    'Story' => 'រឿង',
    'Comp. 1' => 'យល់អត្ថបទ ១',
    'Comp. 2' => 'យល់អត្ថបទ ២',
    
    // Assessment Levels - Math
    'Number Recognition' => 'ស្គាល់លេខ',
    '1-Digit' => 'លេខ១ខ្ទង់',
    '2-Digit' => 'លេខ២ខ្ទង់',
    'Addition' => 'បូក',
    'Subtraction' => 'ដក',
    'Multiplication' => 'គុណ',
    'Division' => 'ចែក',
    'Word Problem' => 'ល្បាយលេខ',
    
    // Assessment Cycles
    'Baseline' => 'មូលដ្ឋាន',
    'Midline' => 'ពាក់កណ្តាល',
    'Endline' => 'ចុងក្រោយ',
    'baseline' => 'មូលដ្ឋាន',
    'midline' => 'ពាក់កណ្តាល',
    'endline' => 'ចុងក្រោយ',
    
    // Filters and Search
    'Filter by School' => 'ត្រងតាមសាលា',
    'Filter by Grade' => 'ត្រងតាមថ្នាក់',
    'Filter by Subject' => 'ត្រងតាមុខវិជ្ជា',
    'Filter by Cycle' => 'ត្រងតាមវដ្ត',
    'Filter by Date Range' => 'ត្រងតាមចន្លោះពេល',
    'Select School' => 'ជ្រើសរើសសាលា',
    'Select Grade' => 'ជ្រើសរើសថ្នាក់',
    'Select Subject' => 'ជ្រើសរើសមុខវិជ្ជា',
    'Select Cycle' => 'ជ្រើសរើសវដ្ត',
    'All Schools' => 'សាលាទាំងអស់',
    'All Grades' => 'ថ្នាក់ទាំងអស់',
    'All Subjects' => 'មុខវិជ្ជាទាំងអស់',
    'All Cycles' => 'វដ្តទាំងអស់',
    'From Date' => 'ចាប់ពីថ្ងៃ',
    'To Date' => 'ដល់ថ្ងៃ',
    'Search' => 'ស្វែងរក',
    'Clear Filters' => 'សម្អាតត្រង',
    
    // Actions
    'View' => 'មើល',
    'View Details' => 'មើលព័ត៌មានលម្អិត',
    'View Report' => 'មើលរបាយការណ៍',
    'View assessment history' => 'មើលប្រវត្តិការវាយតម្លៃ',
    'Generate' => 'បង្កើត',
    'Export' => 'នាំចេញ',
    'Print' => 'បោះពុម្ព',
    'Download' => 'ទាញយក',
    'Back' => 'ត្រឡប់ក្រោយ',
    'Back to Reports' => 'ត្រឡប់ទៅរបាយការណ៍',
    
    // Performance Metrics
    'Average Score' => 'ពិន្ទុមធ្យម',
    'Total Score' => 'ពិន្ទុសរុប',
    'Pass Rate' => 'អត្រាជាប់',
    'Improvement Rate' => 'អត្រាកែលម្អ',
    'Performance Trend' => 'និន្នាការលទ្ធផល',
    'Growth Percentage' => 'ភាគរយកំណើន',
    'Completion Rate' => 'អត្រាបញ្ចប់',
    
    // Chart Labels
    'Performance by Level' => 'លទ្ធផលតាមកម្រិត',
    'Performance Over Time' => 'លទ្ធផលតាមពេលវេលា',
    'School Performance Comparison' => 'ប្រៀបធៀបលទ្ធផលសាលា',
    'Student Progress Chart' => 'តារាងវឌ្ឍនភាពសិស្ស',
    'Assessment Distribution' => 'ការបែងចែកការវាយតម្លៃ',
    'Grade Distribution' => 'ការបែងចែកថ្នាក់',
    
    // Messages
    'No data available' => 'គ្មានទិន្នន័យ',
    'No reports found' => 'រកមិនឃើញរបាយការណ៍',
    'Report generated successfully' => 'បានបង្កើតរបាយការណ៍ដោយជោគជ័យ',
    'Export completed successfully' => 'បាននាំចេញដោយជោគជ័យ',
    'Loading report...' => 'កំពុងផ្ទុករបាយការណ៍...',
    'Generating report...' => 'កំពុងបង្កើតរបាយការណ៍...',
    'Please select filters' => 'សូមជ្រើសរើសត្រង',
    'Please select a date range' => 'សូមជ្រើសរើសចន្លោះពេល',
    
    // Report Sections
    'Summary' => 'សង្ខេប',
    'Detailed Analysis' => 'ការវិភាគលម្អិត',
    'Recommendations' => 'អនុសាសន៍',
    'Key Findings' => 'លទ្ធផលសំខាន់ៗ',
    'Overview' => 'ទិដ្ឋភាពទូទៅ',
    'Statistics' => 'ស្ថិតិ',
    'Trends' => 'និន្នាការ',
    'Comparison' => 'ការប្រៀបធៀប',
    
    // Time Periods
    'Daily' => 'ប្រចាំថ្ងៃ',
    'Weekly' => 'ប្រចាំសប្តាហ៍',
    'Monthly' => 'ប្រចាំខែ',
    'Quarterly' => 'ប្រចាំត្រីមាស',
    'Yearly' => 'ប្រចាំឆ្នាំ',
    'All Time' => 'គ្រប់ពេលវេលា',
    'Last 7 Days' => '៧ថ្ងៃចុងក្រោយ',
    'Last 30 Days' => '៣០ថ្ងៃចុងក្រោយ',
    'Last 3 Months' => '៣ខែចុងក្រោយ',
    'Last 6 Months' => '៦ខែចុងក្រោយ',
    'Last Year' => 'ឆ្នាំមុន',
    'This Year' => 'ឆ្នាំនេះ',
    'Custom Range' => 'ចន្លោះផ្សេង',
    
    // Additional Report-specific Terms
    'Report Title' => 'ចំណងជើងរបាយការណ៍',
    'Report Date' => 'កាលបរិច្ឆេទរបាយការណ៍',
    'Generated By' => 'បង្កើតដោយ',
    'Report Period' => 'រយៈពេលរបាយការណ៍',
    'Data Source' => 'ប្រភពទិន្នន័យ',
    'Total Records' => 'កំណត់ត្រាសរុប',
    'Page' => 'ទំព័រ',
    'of' => 'នៃ',
    
    // Gender Options
    'Male' => 'ប្រុស',
    'Female' => 'ស្រី',
    'All Genders' => 'ភេទទាំងអស់',
    
    // Status Options
    'Active' => 'សកម្ម',
    'Inactive' => 'អសកម្ម',
    'Completed' => 'បានបញ្ចប់',
    'In Progress' => 'កំពុងដំណើរការ',
    'Pending' => 'រង់ចាំ',
];
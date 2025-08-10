<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class ReportTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Report Titles and Headers
            ['key' => 'Student Performance Report', 'en' => 'Student Performance Report', 'km' => 'របាយការណ៍ការអនុវត្តរបស់សិស្ស'],
            ['key' => 'School Comparison Report', 'en' => 'School Comparison Report', 'km' => 'របាយការណ៍ប្រៀបធៀបសាលា'],
            ['key' => 'Mentoring Impact Report', 'en' => 'Mentoring Impact Report', 'km' => 'របាយការណ៍ផលប៉ះពាល់ការណែនាំ'],
            ['key' => 'Progress Tracking Report', 'en' => 'Progress Tracking Report', 'km' => 'របាយការណ៍តាមដានវឌ្ឍនភាព'],
            ['key' => 'Performance Calculation Report', 'en' => 'Performance Calculation Report', 'km' => 'របាយការណ៍គណនាការអនុវត្ត'],
            ['key' => 'Back to Reports', 'en' => 'Back to Reports', 'km' => 'ត្រឡប់ទៅរបាយការណ៍'],

            // Filter Options
            ['key' => 'All Schools', 'en' => 'All Schools', 'km' => 'សាលាទាំងអស់'],
            ['key' => 'All Subjects', 'en' => 'All Subjects', 'km' => 'មុខវិជ្ជាទាំងអស់'],
            ['key' => 'All Cycles', 'en' => 'All Cycles', 'km' => 'រៀងរាល់វដ្តទាំងអស់'],
            ['key' => 'Test Cycle', 'en' => 'Test Cycle', 'km' => 'វដ្តតេស្ត'],
            ['key' => 'Apply Filters', 'en' => 'Apply Filters', 'km' => 'អនុវត្តតម្រង'],

            // Assessment Cycles
            ['key' => 'baseline', 'en' => 'Baseline', 'km' => 'មូលដ្ឋាន'],
            ['key' => 'midline', 'en' => 'Midline', 'km' => 'កណ្តាល'],
            ['key' => 'endline', 'en' => 'Endline', 'km' => 'ចុងក្រោយ'],

            // Level Understanding
            ['key' => 'Understanding Student Performance Levels', 'en' => 'Understanding Student Performance Levels', 'km' => 'យល់ដឹងអំពីកម្រិតការអនុវត្តរបស់សិស្ស'],
            ['key' => 'This report shows how students are distributed across different skill levels in Khmer and Math subjects. Each level represents a specific learning milestone:', 'en' => 'This report shows how students are distributed across different skill levels in Khmer and Math subjects. Each level represents a specific learning milestone:', 'km' => 'របាយការណ៍នេះបង្ហាញពីរបៀបដែលសិស្សត្រូវបានបែងចែកនៅកម្រិតជំនាញផ្សេងៗគ្នានៅក្នុងមុខវិជ្ជាខ្មែរ និងគណិតវិទ្យា។ កម្រិតនីមួយៗតំណាងឱ្យចំណុចសម្គាល់នៃការរៀនសូត្រជាក់លាក់មួយ៖'],

            // Khmer Levels
            ['key' => 'Khmer Levels', 'en' => 'Khmer Levels', 'km' => 'កម្រិតខ្មែរ'],
            ['key' => 'Cannot read letters or words', 'en' => 'Cannot read letters or words', 'km' => 'មិនអាចអានអក្សរ ឬពាក្យបាន'],
            ['key' => 'Can identify individual letters', 'en' => 'Can identify individual letters', 'km' => 'អាចសម្គាល់អក្សរនីមួយៗបាន'],
            ['key' => 'Can read simple words', 'en' => 'Can read simple words', 'km' => 'អាចអានពាក្យសាមញ្ញបាន'],
            ['key' => 'Can read simple paragraphs', 'en' => 'Can read simple paragraphs', 'km' => 'អាចអានកថាខណ្ឌសាមញ្ញបាន'],
            ['key' => 'Can read complete stories', 'en' => 'Can read complete stories', 'km' => 'អាចអានរឿងពេញលេញបាន'],
            ['key' => 'Basic reading comprehension', 'en' => 'Basic reading comprehension', 'km' => 'ការយល់ដឹងការអានមូលដ្ឋាន'],
            ['key' => 'Advanced reading comprehension', 'en' => 'Advanced reading comprehension', 'km' => 'ការយល់ដឹងការអានកម្រិតខ្ពស់'],

            // Math Levels
            ['key' => 'Math Levels', 'en' => 'Math Levels', 'km' => 'កម្រិតគណិតវិទ្យា'],
            ['key' => 'Cannot perform basic math operations', 'en' => 'Cannot perform basic math operations', 'km' => 'មិនអាចធ្វើប្រមាណវិធីគណិតវិទ្យាមូលដ្ឋានបាន'],
            ['key' => 'Can work with single-digit numbers', 'en' => 'Can work with single-digit numbers', 'km' => 'អាចធ្វើការជាមួយលេខឯកតួបាន'],
            ['key' => 'Can work with two-digit numbers', 'en' => 'Can work with two-digit numbers', 'km' => 'អាចធ្វើការជាមួយលេខពីរតួបាន'],
            ['key' => 'Can perform subtraction operations', 'en' => 'Can perform subtraction operations', 'km' => 'អាចធ្វើប្រមាណវិធីដកបាន'],
            ['key' => 'Can perform division operations', 'en' => 'Can perform division operations', 'km' => 'អាចធ្វើប្រមាណវិធីចែកបាន'],
            ['key' => 'Can solve mathematical word problems', 'en' => 'Can solve mathematical word problems', 'km' => 'អាចដោះស្រាយបញ្ហាគណិតវិទ្យាជាពាក្យបាន'],

            // Assessment Cycles Explanation
            ['key' => 'Assessment Cycles', 'en' => 'Assessment Cycles', 'km' => 'វដ្តវាយតម្លៃ'],
            ['key' => 'Initial assessment to determine starting skill levels', 'en' => 'Initial assessment to determine starting skill levels', 'km' => 'ការវាយតម្លៃដំបូងដើម្បីកំណត់កម្រិតជំនាញចាប់ផ្តើម'],
            ['key' => 'Mid-program assessment to track progress', 'en' => 'Mid-program assessment to track progress', 'km' => 'ការវាយតម្លៃកណ្តាលកម្មវិធីដើម្បីតាមដានវឌ្ឍនភាព'],
            ['key' => 'Final assessment to measure overall improvement', 'en' => 'Final assessment to measure overall improvement', 'km' => 'ការវាយតម្លៃចុងក្រោយដើម្បីវាស់វែងការធ្វើឱ្យប្រសើរឡើងជាទូទៅ'],

            // Chart Titles
            ['key' => 'Khmer Performance by Level', 'en' => 'Khmer Performance by Level', 'km' => 'ការអនុវត្តខ្មែរតាមកម្រិត'],
            ['key' => 'Math Performance by Level', 'en' => 'Math Performance by Level', 'km' => 'ការអនុវត្តគណិតវិទ្យាតាមកម្រិត'],
            ['key' => 'Performance by Level', 'en' => 'Performance by Level', 'km' => 'ការអនុវត្តតាមកម្រិត'],

            // Chart Descriptions
            ['key' => 'What this shows', 'en' => 'What this shows', 'km' => 'អ្វីដែលវាបង្ហាញ'],
            ['key' => 'Distribution of students across Khmer reading levels. Colors progress from red (lowest) to teal (highest) to visually represent skill progression.', 'en' => 'Distribution of students across Khmer reading levels. Colors progress from red (lowest) to teal (highest) to visually represent skill progression.', 'km' => 'ការបែងចែកសិស្សនៅកម្រិតការអានខ្មែរ។ ពណ៌វិវឌ្ឍនភាពពីក្រហម (ទាបបំផុត) ទៅ teal (ខ្ពស់បំផុត) ដើម្បីបង្ហាញការវិវឌ្ឍនភាពជំនាញដោយមើលឃើញ។'],
            ['key' => 'Distribution of students across Math skill levels. Colors progress from red (lowest) to emerald (highest) to visually represent skill progression.', 'en' => 'Distribution of students across Math skill levels. Colors progress from red (lowest) to emerald (highest) to visually represent skill progression.', 'km' => 'ការបែងចែកសិស្សនៅកម្រិតជំនាញគណិតវិទ្យា។ ពណ៌វិវឌ្ឍនភាពពីក្រហម (ទាបបំផុត) ទៅ emerald (ខ្ពស់បំផុត) ដើម្បីបង្ហាញការវិវឌ្ឍនភាពជំនាញដោយមើលឃើញ។'],
            ['key' => 'Distribution of students across Khmer reading levels. Each student is assessed and placed at the level that best matches their reading ability.', 'en' => 'Distribution of students across Khmer reading levels. Each student is assessed and placed at the level that best matches their reading ability.', 'km' => 'ការបែងចែកសិស្សនៅកម្រិតការអានខ្មែរ។ សិស្សនីមួយៗត្រូវបានវាយតម្លៃ និងដាក់នៅកម្រិតដែលត្រូវគ្នានឹងសមត្ថភាពអានរបស់ពួកគេបំផុត។'],
            ['key' => 'Distribution of students across Math skill levels. Each student is assessed and placed at the level that best matches their mathematical ability.', 'en' => 'Distribution of students across Math skill levels. Each student is assessed and placed at the level that best matches their mathematical ability.', 'km' => 'ការបែងចែកសិស្សនៅកម្រិតជំនាញគណិតវិទ្យា។ សិស្សនីមួយៗត្រូវបានវាយតម្លៃ និងដាក់នៅកម្រិតដែលត្រូវគ្នានឹងសមត្ថភាពគណិតវិទ្យារបស់ពួកគេបំផុត។'],
            ['key' => 'Color coding', 'en' => 'Color coding', 'km' => 'កំណត់ពណ៌'],
            ['key' => 'Colors progress from red (beginner) to green/teal (advanced) to show skill progression.', 'en' => 'Colors progress from red (beginner) to green/teal (advanced) to show skill progression.', 'km' => 'ពណ៌វិវឌ្ឍនភាពពីក្រហម (ចាប់ផ្តើម) ទៅបៃតង/teal (កម្រិតខ្ពស់) ដើម្បីបង្ហាញការវិវឌ្ឍនភាពជំនាញ។'],

            // Table Headers
            ['key' => 'Level', 'en' => 'Level', 'km' => 'កម្រិត'],
            ['key' => 'Number of Students', 'en' => 'Number of Students', 'km' => 'ចំនួនសិស្ស'],
            ['key' => 'Percentage', 'en' => 'Percentage', 'km' => 'ភាគរយ'],
            ['key' => 'Total', 'en' => 'Total', 'km' => 'សរុប'],

            // Tooltips
            ['key' => 'Khmer reading skill level', 'en' => 'Khmer reading skill level', 'km' => 'កម្រិតជំនាញអានខ្មែរ'],
            ['key' => 'Math skill level', 'en' => 'Math skill level', 'km' => 'កម្រិតជំនាញគណិតវិទ្យា'],
            ['key' => 'Skill level determined by assessment', 'en' => 'Skill level determined by assessment', 'km' => 'កម្រិតជំនាញកំណត់ដោយការវាយតម្លៃ'],
            ['key' => 'Total students assessed at this level', 'en' => 'Total students assessed at this level', 'km' => 'សិស្សសរុបដែលបានវាយតម្លៃនៅកម្រិតនេះ'],
            ['key' => 'Percentage of all Khmer assessments', 'en' => 'Percentage of all Khmer assessments', 'km' => 'ភាគរយនៃការវាយតម្លៃខ្មែរទាំងអស់'],
            ['key' => 'Percentage of all Math assessments', 'en' => 'Percentage of all Math assessments', 'km' => 'ភាគរយនៃការវាយតម្លៃគណិតវិទ្យាទាំងអស់'],
            ['key' => 'Percentage of all assessments for this subject', 'en' => 'Percentage of all assessments for this subject', 'km' => 'ភាគរយនៃការវាយតម្លៃទាំងអស់សម្រាប់មុខវិជ្ជានេះ'],

            // Data Interpretation
            ['key' => 'How to Interpret This Data', 'en' => 'How to Interpret This Data', 'km' => 'របៀបបកស្រាយទិន្នន័យនេះ'],
            ['key' => 'Higher level distributions', 'en' => 'Higher level distributions', 'km' => 'ការបែងចែកកម្រិតខ្ពស់'],
            ['key' => 'More students at advanced levels indicates stronger overall performance', 'en' => 'More students at advanced levels indicates stronger overall performance', 'km' => 'សិស្សច្រើននៅកម្រិតខ្ពស់បង្ហាញពីការអនុវត្តជាទូទៅកាន់តែល្អ'],
            ['key' => 'Beginner concentrations', 'en' => 'Beginner concentrations', 'km' => 'ការប្រមូលផ្តុំអ្នកចាប់ផ្តើម'],
            ['key' => 'Large numbers at beginner level may indicate need for foundational support', 'en' => 'Large numbers at beginner level may indicate need for foundational support', 'km' => 'ចំនួនច្រើននៅកម្រិតចាប់ផ្តើមអាចបង្ហាញពីតម្រូវការសម្រាប់ការគាំទ្រមូលដ្ឋាន'],
            ['key' => 'Even distributions', 'en' => 'Even distributions', 'km' => 'ការបែងចែកស្មើ'],
            ['key' => 'Students spread across levels suggests mixed abilities requiring differentiated instruction', 'en' => 'Students spread across levels suggests mixed abilities requiring differentiated instruction', 'km' => 'សិស្សបែកបាក់នៅកម្រិតផ្សេងៗបង្ហាញពីសមត្ថភាពចម្រុះដែលត្រូវការការបង្រៀនខុសៗគ្នា'],
            ['key' => 'Filter usage', 'en' => 'Filter usage', 'km' => 'ការប្រើប្រាស់តម្រង'],
            ['key' => 'Use school, subject, and cycle filters to analyze specific groups or track progress over time', 'en' => 'Use school, subject, and cycle filters to analyze specific groups or track progress over time', 'km' => 'ប្រើតម្រងសាលា មុខវិជ្ជា និងវដ្តដើម្បីវិភាគក្រុមជាក់លាក់ ឬតាមដានវឌ្ឍនភាពតាមពេលវេលា'],

            // Export Options
            ['key' => 'Export Options', 'en' => 'Export Options', 'km' => 'ជម្រើសនាំចេញ'],
            ['key' => 'Save charts and data for presentations, reports, or further analysis.', 'en' => 'Save charts and data for presentations, reports, or further analysis.', 'km' => 'រក្សាទុកតារាង និងទិន្នន័យសម្រាប់ការបង្ហាញ របាយការណ៍ ឬការវិភាគបន្ថែម។'],
            ['key' => 'Export Charts as Images', 'en' => 'Export Charts as Images', 'km' => 'នាំចេញតារាងជារូបភាព'],
            ['key' => 'Print Report', 'en' => 'Print Report', 'km' => 'បោះពុម្ពរបាយការណ៍'],

            // Chart Labels and JavaScript
            ['key' => 'Student Distribution by Level', 'en' => 'Student Distribution by Level', 'km' => 'ការបែងចែកសិស្សតាមកម្រិត'],
            ['key' => 'students', 'en' => 'students', 'km' => 'សិស្ស'],
            ['key' => 'Skill Level', 'en' => 'Skill Level', 'km' => 'កម្រិតជំនាញ'],
            ['key' => 'Beginner to Advanced', 'en' => 'Beginner to Advanced', 'km' => 'ពីចាប់ផ្តើមទៅកម្រិតខ្ពស់'],

            // Level Names
            ['key' => 'Beginner', 'en' => 'Beginner', 'km' => 'ចាប់ផ្តើម'],
            ['key' => 'Letter', 'en' => 'Letter', 'km' => 'អក្សរ'],
            ['key' => 'Word', 'en' => 'Word', 'km' => 'ពាក្យ'],
            ['key' => 'Paragraph', 'en' => 'Paragraph', 'km' => 'កថាខណ្ឌ'],
            ['key' => 'Story', 'en' => 'Story', 'km' => 'រឿង'],
            ['key' => 'Comp. 1', 'en' => 'Comp. 1', 'km' => 'យល់ ១'],
            ['key' => 'Comp. 2', 'en' => 'Comp. 2', 'km' => 'យល់ ២'],
            ['key' => '1-Digit', 'en' => '1-Digit', 'km' => '១ តួ'],
            ['key' => '2-Digit', 'en' => '2-Digit', 'km' => '២ តួ'],
            ['key' => 'Subtraction', 'en' => 'Subtraction', 'km' => 'ដក'],
            ['key' => 'Division', 'en' => 'Division', 'km' => 'ចែក'],
            ['key' => 'Word Problem', 'en' => 'Word Problem', 'km' => 'បញ្ហាពាក្យ'],

            // Subject Names
            ['key' => 'Khmer', 'en' => 'Khmer', 'km' => 'ខ្មែរ'],
            ['key' => 'Math', 'en' => 'Math', 'km' => 'គណិតវិទ្យា'],

            // General Terms
            ['key' => 'School', 'en' => 'School', 'km' => 'សាលា'],
            ['key' => 'Subject', 'en' => 'Subject', 'km' => 'មុខវិជ្ជា'],

            // School Comparison Report - Additional Translations
            ['key' => 'Understanding School Comparisons', 'en' => 'Understanding School Comparisons', 'km' => 'យល់ដឹងអំពីការប្រៀបធៀបសាលា'],
            ['key' => 'This report compares student performance across different schools for a specific subject and assessment cycle. It helps identify:', 'en' => 'This report compares student performance across different schools for a specific subject and assessment cycle. It helps identify:', 'km' => 'របាយការណ៍នេះប្រៀបធៀបការអនុវត្តរបស់សិស្សទូទាំងសាលាផ្សេងៗគ្នាសម្រាប់មុខវិជ្ជា និងវដ្តវាយតម្លៃជាក់លាក់មួយ។ វាជួយកំណត់អត្តសញ្ញាណ៖'],
            ['key' => 'Schools with higher concentrations of advanced students', 'en' => 'Schools with higher concentrations of advanced students', 'km' => 'សាលាដែលមានការប្រមូលផ្តុំខ្ពស់នៅសិស្សកម្រិតខ្ពស់'],
            ['key' => 'Schools that may need additional support for foundational skills', 'en' => 'Schools that may need additional support for foundational skills', 'km' => 'សាលាដែលអាចត្រូវការការគាំទ្របន្ថែមសម្រាប់ជំនាញមូលដ្ឋាន'],
            ['key' => 'Distribution patterns that inform resource allocation', 'en' => 'Distribution patterns that inform resource allocation', 'km' => 'លំនាំការបែងចែកដែលផ្តល់ព័ត៌មានការបែងចែកធនធាន'],
            ['key' => 'Performance variations across the school network', 'en' => 'Performance variations across the school network', 'km' => 'ការប្រែប្រួលការអនុវត្តនៅទូទាំងបណ្តាញសាលា'],

            // School Comparison Chart and Table Translations
            ['key' => 'How to Read the Charts and Tables', 'en' => 'How to Read the Charts and Tables', 'km' => 'របៀបអានគំនូសតាង និងតារាង'],
            ['key' => 'Stacked Bar Chart', 'en' => 'Stacked Bar Chart', 'km' => 'គំនូសតាងរបារជង់'],
            ['key' => 'Each bar represents one school', 'en' => 'Each bar represents one school', 'km' => 'របារនីមួយៗតំណាងឱ្យសាលាមួយ'],
            ['key' => 'Colors show skill levels (red=beginner, green/teal=advanced)', 'en' => 'Colors show skill levels (red=beginner, green/teal=advanced)', 'km' => 'ពណ៌បង្ហាញកម្រិតជំនាញ (ក្រហម=ដំបូង, បៃតង/បៃតងទឹកសមុទ្រ=កម្រិតខ្ពស់)'],
            ['key' => 'Bar height shows total students assessed', 'en' => 'Bar height shows total students assessed', 'km' => 'កម្ពស់របារបង្ហាញចំនួនសិស្សដែលបានវាយតម្លៃសរុប'],
            ['key' => 'Color proportions show level distribution', 'en' => 'Color proportions show level distribution', 'km' => 'សមាមាត្រពណ៌បង្ហាញការបែងចែកកម្រិត'],
            ['key' => 'Comparison Table', 'en' => 'Comparison Table', 'km' => 'តារាងប្រៀបធៀប'],
            ['key' => 'Numbers show student counts at each level', 'en' => 'Numbers show student counts at each level', 'km' => 'លេខបង្ហាញចំនួនសិស្សនៅកម្រិតនីមួយៗ'],
            ['key' => 'Percentages show proportion of school total', 'en' => 'Percentages show proportion of school total', 'km' => 'ភាគរយបង្ហាញសមាមាត្រនៃចំនួនសរុបសាលា'],
            ['key' => 'Compare across rows to see school differences', 'en' => 'Compare across rows to see school differences', 'km' => 'ប្រៀបធៀបតាមជួរដេកដើម្បីមើលភាពខុសគ្នារបស់សាលា'],
            ['key' => 'Compare within columns to see level concentrations', 'en' => 'Compare within columns to see level concentrations', 'km' => 'ប្រៀបធៀបក្នុងជួរឈរដើម្បីមើលការប្រមូលផ្តុំកម្រិត'],

            // School Comparison Chart Specific Translations
            ['key' => 'Student Distribution by School and Level', 'en' => 'Student Distribution by School and Level', 'km' => 'ការបែងចែកសិស្សតាមសាលា និងកម្រិត'],
            ['key' => 'Chart interpretation', 'en' => 'Chart interpretation', 'km' => 'ការបកស្រាយគំនូសតាង'],
            ['key' => 'Each school is represented by a stacked bar. Taller bars indicate more students assessed. The color distribution within each bar shows how students are distributed across skill levels.', 'en' => 'Each school is represented by a stacked bar. Taller bars indicate more students assessed. The color distribution within each bar shows how students are distributed across skill levels.', 'km' => 'សាលានីមួយៗត្រូវបានតំណាងដោយរបារជង់។ របារខ្ពស់បង្កើតបង្ហាញថាសិស្សច្រើនជាងត្រូវបានវាយតម្លៃ។ ការបែងចែកពណ៌នៅក្នុងរបារនីមួយៗបង្ហាញពីរបៀបដែលសិស្សត្រូវបានបែងចែកនៅទូទាំងកម្រិតជំនាញ។'],
            ['key' => 'Look for', 'en' => 'Look for', 'km' => 'មើលរក'],
            ['key' => 'Schools with more green/teal (advanced levels) vs red/yellow (beginner levels), and schools with similar total heights but different color distributions.', 'en' => 'Schools with more green/teal (advanced levels) vs red/yellow (beginner levels), and schools with similar total heights but different color distributions.', 'km' => 'សាលាដែលមានពណ៌បៃតង/បៃតងទឹកសមុទ្រច្រើនជាង (កម្រិតខ្ពស់) ធៀបនឹងក្រហម/លឿង (កម្រិតដំបូង) និងសាលាដែលមានកម្ពស់សរុបស្រដៀងគ្នាប៉ុន្តែការបែងចែកពណ៌ផ្សេងគ្នា។'],

            // Mentoring Impact Report Translations
            ['key' => 'How We Calculate Impact', 'en' => 'How We Calculate Impact', 'km' => 'របៀបដែលយើងគណនាផលប៉ះពាល់'],
            ['key' => 'Baseline Percentage', 'en' => 'Baseline Percentage', 'km' => 'ភាគរយមូលដ្ឋាន'],
            ['key' => 'Latest Percentage', 'en' => 'Latest Percentage', 'km' => 'ភាគរយចុងក្រោយ'],
            ['key' => 'Mentoring Visits vs Performance Improvement', 'en' => 'Mentoring Visits vs Performance Improvement', 'km' => 'ការចូលរួមណែនាំ ធៀបនឹង ការកែលម្អការអនុវត្ត'],
            ['key' => 'School Impact Summary', 'en' => 'School Impact Summary', 'km' => 'សង្ខេបផលប៉ះពាល់សាលា'],
            ['key' => 'Understanding the Data:', 'en' => 'Understanding the Data:', 'km' => 'ការយល់ដឹងទិន្នន័យ៖'],
            ['key' => 'Visit Details by School', 'en' => 'Visit Details by School', 'km' => 'លម្អិតការចូលរួមតាមសាលា'],
            ['key' => 'Teachers', 'en' => 'Teachers', 'km' => 'គ្រូ'],
            ['key' => 'Follow-up Required', 'en' => 'Follow-up Required', 'km' => 'ត្រូវការតាមដាន'],
            ['key' => 'Show Details', 'en' => 'Show Details', 'km' => 'បង្ហាញលម្អិត'],
            ['key' => 'Hide Details', 'en' => 'Hide Details', 'km' => 'លាក់លម្អិត'],

            // Chart translations for Mentoring Impact Report
            ['key' => 'visits', 'en' => 'visits', 'km' => 'ការចុះណែនាំ'],
            ['key' => 'improvement', 'en' => 'improvement', 'km' => 'កែលម្អ'],
            ['key' => 'Correlation between Mentoring Visits and Performance Improvement', 'en' => 'Correlation between Mentoring Visits and Performance Improvement', 'km' => 'ការទាក់ទងគ្នារវាងការចុះណែនាំ និងការកែលម្អដំណើរការ'],
            ['key' => 'Number of Mentoring Visits', 'en' => 'Number of Mentoring Visits', 'km' => 'ចំនួនការចុះណែនាំ'],
            ['key' => 'Performance Improvement (Latest - Baseline)', 'en' => 'Performance Improvement (Latest - Baseline)', 'km' => 'ការកែលម្អដំណើរការ (ចុងក្រោយ - មូលដ្ឋាន)'],

            // Additional Mentoring Impact translations
            ['key' => 'Percentage of visits where class was in session', 'en' => 'Percentage of visits where class was in session', 'km' => 'ភាគរយនៃការចុះទស្សនកិច្ចដែលថ្នាក់រៀនកំពុងបើក'],
            ['key' => 'Percentage of teachers with session plans', 'en' => 'Percentage of teachers with session plans', 'km' => 'ភាគរយនៃគ្រូដែលមានផែនការបង្រៀន'],
            ['key' => 'Individual visit records during the selected period:', 'en' => 'Individual visit records during the selected period:', 'km' => 'កំណត់ត្រាការចុះទស្សនកិច្ចបុគ្គលក្នុងអំឡុងពេលដែលបានជ្រើសរើស៖'],
            ['key' => 'Date', 'en' => 'Date', 'km' => 'កាលបរិច្ឆេទ'],
            ['key' => 'Teacher', 'en' => 'Teacher', 'km' => 'គ្រូ'],
            ['key' => 'Session', 'en' => 'Session', 'km' => 'សេស្យុង'],
            ['key' => 'Follow-up', 'en' => 'Follow-up', 'km' => 'តាមដាន'],
            ['key' => 'Yes', 'en' => 'Yes', 'km' => 'បាទ/ចាស'],
            ['key' => 'No', 'en' => 'No', 'km' => 'ទេ'],
            ['key' => 'Start Date', 'en' => 'Start Date', 'km' => 'កាលបរិច្ឆេទចាប់ផ្តើម'],
            ['key' => 'End Date', 'en' => 'End Date', 'km' => 'កាលបរិច្ឆេទបញ្ចប់'],

            // Progress Tracking Report Translations
            ['key' => 'Understanding Progress Tracking', 'en' => 'Understanding Progress Tracking', 'km' => 'យល់ដឹងការតាមដានវឌ្ឍនភាព'],
            ['key' => 'This report tracks individual student learning progress by comparing their baseline assessment with their most recent assessment (midline or endline). It shows:', 'en' => 'This report tracks individual student learning progress by comparing their baseline assessment with their most recent assessment (midline or endline). It shows:', 'km' => 'របាយការណ៍នេះតាមដានវឌ្ឍនភាពការរៀនសូត្រសិស្សបុគ្គលដោយប្រៀបធៀបការវាយតម្លៃមូលដ្ឋានរបស់ពួកគេជាមួយការវាយតម្លៃចុងក្រោយបំផុតរបស់ពួកគេ (មុនពេលកណ្តាល ឬចុងក្រោយ)។ វាបង្ហាញ៖'],
            ['key' => 'Students who improved to higher skill levels', 'en' => 'Students who improved to higher skill levels', 'km' => 'សិស្សដែលបានកែលម្អទៅកម្រិតជំនាញខ្ពស់'],
            ['key' => 'Students who maintained their skill level', 'en' => 'Students who maintained their skill level', 'km' => 'សិស្សដែលរក្សាបានកម្រិតជំនាញរបស់ពួកគេ'],
            ['key' => 'Students whose performance declined', 'en' => 'Students whose performance declined', 'km' => 'សិស្សដែលការអនុវត្តបានធ្លាក់ចុះ'],
            ['key' => 'Score changes between assessment cycles', 'en' => 'Score changes between assessment cycles', 'km' => 'ការផ្លាស់ប្តូរពិន្ទុរវាងវដ្តវាយតម្លៃ'],

            // Progress Calculation Details
            ['key' => 'Compares skill levels between baseline and latest assessment', 'en' => 'Compares skill levels between baseline and latest assessment', 'km' => 'ប្រៀបធៀបកម្រិតជំនាញរវាងការវាយតម្លៃមូលដ្ឋាន និងចុងក្រោយ'],
            ['key' => 'Positive number = moved up levels (improved)', 'en' => 'Positive number = moved up levels (improved)', 'km' => 'លេខវិជ្ជមាន = បានឡើងកម្រិត (កែលម្អ)'],
            ['key' => 'Zero = stayed at same level (maintained)', 'en' => 'Zero = stayed at same level (maintained)', 'km' => 'សូន្យ = នៅតែកម្រិតដដែល (រក្សាបាន)'],
            ['key' => 'Negative number = moved down levels (declined)', 'en' => 'Negative number = moved down levels (declined)', 'km' => 'លេខអវិជ្ជមាន = ចុះកម្រិត (ធ្លាក់ចុះ)'],
            ['key' => 'Difference between latest score and baseline score', 'en' => 'Difference between latest score and baseline score', 'km' => 'ភាពខុសគ្នារវាងពិន្ទុចុងក្រោយ និងពិន្ទុមូលដ្ឋាន'],
            ['key' => 'Positive = score increased', 'en' => 'Positive = score increased', 'km' => 'វិជ្ជមាន = ពិន្ទុកើនឡើង'],
            ['key' => 'Zero = score stayed the same', 'en' => 'Zero = score stayed the same', 'km' => 'សូន្យ = ពិន្ទុនៅដដែល'],
            ['key' => 'Negative = score decreased', 'en' => 'Negative = score decreased', 'km' => 'អវិជ្ជមាន = ពិន្ទុចុះទាប'],

            // Current View Section
            ['key' => 'Current View', 'en' => 'Current View', 'km' => 'ទិដ្ឋភាពបច្ចុប្បន្ន'],
            ['key' => 'Tracking progress in', 'en' => 'Tracking progress in', 'km' => 'តាមដានវឌ្ឍនភាពក្នុង'],
            ['key' => 'for', 'en' => 'for', 'km' => 'សម្រាប់'],
            ['key' => 'Selected School', 'en' => 'Selected School', 'km' => 'សាលាដែលបានជ្រើសរើស'],
            ['key' => 'across all accessible schools', 'en' => 'across all accessible schools', 'km' => 'នៅទូទាំងសាលាដែលអាចចូលប្រើបាន'],
            ['key' => 'Only students with both baseline and follow-up assessments are included', 'en' => 'Only students with both baseline and follow-up assessments are included', 'km' => 'មានតែសិស្សដែលមានការវាយតម្លៃមូលដ្ឋាន និងតាមដានប៉ុណ្ណោះដែលត្រូវបានរួមបញ្ចូល'],

            // Individual Student Progress
            ['key' => 'Individual Student Progress', 'en' => 'Individual Student Progress', 'km' => 'វឌ្ឍនភាពសិស្សបុគ្គល'],
            ['key' => 'Reading the Progress Table', 'en' => 'Reading the Progress Table', 'km' => 'ការអានតារាងវឌ្ឍនភាព'],
            ['key' => 'Baseline', 'en' => 'Baseline', 'km' => 'មូលដ្ឋាន'],
            ['key' => 'Initial skill level and score when first assessed', 'en' => 'Initial skill level and score when first assessed', 'km' => 'កម្រិតជំនាញ និងពិន្ទុដំបូងពេលវាយតម្លៃលើកដំបូង'],
            ['key' => 'Latest', 'en' => 'Latest', 'km' => 'ចុងក្រោយ'],
            ['key' => 'Most recent skill level and score (midline or endline)', 'en' => 'Most recent skill level and score (midline or endline)', 'km' => 'កម្រិតជំនាញ និងពិន្ទុថ្មីបំផុត (កណ្តាល ឬចុងក្រោយ)'],
            ['key' => 'Number of skill levels moved up (+) or down (-)', 'en' => 'Number of skill levels moved up (+) or down (-)', 'km' => 'ចំនួនកម្រិតជំនាញឡើង (+) ឬចុះ (-)'],
            ['key' => 'Numerical difference between latest and baseline scores', 'en' => 'Numerical difference between latest and baseline scores', 'km' => 'ភាពខុសគ្នាជាលេខរវាងពិន្ទុចុងក្រោយ និងមូលដ្ឋាន'],
            ['key' => 'Students are sorted by level improvement (most improved first), then by score change.', 'en' => 'Students are sorted by level improvement (most improved first), then by score change.', 'km' => 'សិស្សត្រូវបានតម្រៀបតាមការកែលម្អកម្រិត (អ្នកកែលម្អបំផុតមុន) បន្ទាប់មកតាមការផ្លាស់ប្តូរពិន្ទុ។'],

            // Table Headers and Tooltips
            ['key' => 'Student Name', 'en' => 'Student Name', 'km' => 'ឈ្មោះសិស្ស'],
            ['key' => 'Student name', 'en' => 'Student name', 'km' => 'ឈ្មោះសិស្ស'],
            ['key' => 'School where student is enrolled', 'en' => 'School where student is enrolled', 'km' => 'សាលាដែលសិស្សចុះឈ្មោះ'],
            ['key' => 'Initial assessment level and score', 'en' => 'Initial assessment level and score', 'km' => 'កម្រិត និងពិន្ទុការវាយតម្លៃដំបូង'],
            ['key' => 'Cycle', 'en' => 'Cycle', 'km' => 'វដ្ត'],
            ['key' => 'Most recent assessment level and score', 'en' => 'Most recent assessment level and score', 'km' => 'កម្រិត និងពិន្ទុការវាយតម្លៃថ្មីបំផុត'],
            ['key' => 'Change in skill levels (+ improved, - declined, = same)', 'en' => 'Change in skill levels (+ improved, - declined, = same)', 'km' => 'ការផ្លាស់ប្តូរកម្រិតជំនាញ (+ កែលម្អ, - ធ្លាក់ចុះ, = ដូចគ្នា)'],
            ['key' => 'Numerical score difference (latest - baseline)', 'en' => 'Numerical score difference (latest - baseline)', 'km' => 'ភាពខុសគ្នាពិន្ទុជាលេខ (ចុងក្រោយ - មូលដ្ឋាន)'],

            // Empty State Messages
            ['key' => 'No students with multiple assessments found', 'en' => 'No students with multiple assessments found', 'km' => 'រកមិនឃើញសិស្សដែលមានការវាយតម្លៃច្រើនទេ'],
            ['key' => 'Students need both baseline and follow-up assessments to appear in this report', 'en' => 'Students need both baseline and follow-up assessments to appear in this report', 'km' => 'សិស្សត្រូវការការវាយតម្លៃមូលដ្ឋាន និងតាមដានទាំងពីរដើម្បីបង្ហាញក្នុងរបាយការណ៍នេះ'],

            // Progress Status Labels
            ['key' => 'level', 'en' => 'level', 'km' => 'កម្រិត'],
            ['key' => 'levels', 'en' => 'levels', 'km' => 'កម្រិត'],
            ['key' => 'Same level', 'en' => 'Same level', 'km' => 'កម្រិតដូចគ្នា'],

            // Additional Progress Summary Translations
            ['key' => 'Summary Cards', 'en' => 'Summary Cards', 'km' => 'កាតសង្ខេប'],
            ['key' => 'Each card shows the count and percentage of students in each progress category. The average score change shows the overall trend across all students.', 'en' => 'Each card shows the count and percentage of students in each progress category. The average score change shows the overall trend across all students.', 'km' => 'កាតនីមួយៗបង្ហាញចំនួន និងភាគរយសិស្សនៅក្នុងប្រភេទវឌ្ឍនភាពនីមួយៗ។ ការផ្លាស់ប្តូរពិន្ទុជាមធ្យមបង្ហាញទិន្នន័យទូទៅនៅទូទាំងសិស្សទាំងអស់។'],
            ['key' => 'of total', 'en' => 'of total', 'km' => 'នៃសរុប'],
            ['key' => 'Moved to higher skill levels', 'en' => 'Moved to higher skill levels', 'km' => 'ផ្លាស់ទីទៅកម្រិតជំនាញខ្ពស់'],
            ['key' => 'Stayed at same skill level', 'en' => 'Stayed at same skill level', 'km' => 'នៅតែកម្រិតជំនាញដដែល'],
            ['key' => 'Moved to lower skill levels', 'en' => 'Moved to lower skill levels', 'km' => 'ផ្លាស់ទីទៅកម្រិតជំនាញទាប'],
            ['key' => 'Avg Score Change', 'en' => 'Avg Score Change', 'km' => 'ការផ្លាស់ប្តូរពិន្ទុជាមធ្យម'],
            ['key' => 'Overall improvement trend', 'en' => 'Overall improvement trend', 'km' => 'ទិន្នន័យកែលម្អទូទៅ'],
            ['key' => 'Overall decline trend', 'en' => 'Overall decline trend', 'km' => 'ទិន្នន័យធ្លាក់ចុះទូទៅ'],
            ['key' => 'No overall change', 'en' => 'No overall change', 'km' => 'គ្មានការផ្លាស់ប្តូរទូទៅ'],
            ['key' => 'Progress Distribution Chart', 'en' => 'Progress Distribution Chart', 'km' => 'តារាងបែងចែកវឌ្ឍនភាព'],
            ['key' => 'This doughnut chart shows the proportion of students in each progress category. Larger green sections indicate more students improved, while larger red sections may indicate need for intervention.', 'en' => 'This doughnut chart shows the proportion of students in each progress category. Larger green sections indicate more students improved, while larger red sections may indicate need for intervention.', 'km' => 'តារាងដូណាតនេះបង្ហាញសមាមាត្រសិស្សនៅក្នុងប្រភេទវឌ្ឍនភាពនីមួយៗ។ ផ្នែកបៃតងធំជាងបង្ហាញថាសិស្សកាន់តែច្រើនបានកែលម្អ ខណៈផ្នែកក្រហមធំជាងអាចបង្ហាញពីតម្រូវការអន្តរាគមន៍។'],

            // Interpretation Guide Translations
            ['key' => 'Interpreting Progress Data', 'en' => 'Interpreting Progress Data', 'km' => 'បកស្រាយទិន្នន័យវឌ្ឍនភាព'],
            ['key' => 'High improvement rates', 'en' => 'High improvement rates', 'km' => 'អត្រាកែលម្អខ្ពស់'],
            ['key' => 'Indicates effective teaching and learning interventions', 'en' => 'Indicates effective teaching and learning interventions', 'km' => 'បង្ហាញពីអន្តរាគមន៍បង្រៀន និងរៀនសូត្រប្រកបដោយប្រសិទ្ធភាព'],
            ['key' => 'Mixed results', 'en' => 'Mixed results', 'km' => 'លទ្ធផលចម្រុះ'],
            ['key' => 'Normal variation; consider individual student needs', 'en' => 'Normal variation; consider individual student needs', 'km' => 'ការប្រែប្រួលធម្មតា; ពិចារណាតម្រូវការសិស្សបុគ្គល'],
            ['key' => 'Low improvement rates', 'en' => 'Low improvement rates', 'km' => 'អត្រាកែលម្អទាប'],
            ['key' => 'May indicate need for curriculum or teaching method review', 'en' => 'May indicate need for curriculum or teaching method review', 'km' => 'អាចបង្ហាញពីតម្រូវការពិនិត្យឡើងវិញកម្មវិធីសិក្សា ឬវិធីសាស្ត្របង្រៀន'],
            ['key' => 'Students maintaining levels', 'en' => 'Students maintaining levels', 'km' => 'សិស្សរក្សាកម្រិត'],
            ['key' => 'Could be at appropriate challenge level or need different support', 'en' => 'Could be at appropriate challenge level or need different support', 'km' => 'អាចនៅកម្រិតប្រឈមសមរម្យ ឬត្រូវការការគាំទ្រផ្សេងគ្នា'],
            ['key' => 'Students declining', 'en' => 'Students declining', 'km' => 'សិស្សធ្លាក់ចុះ'],
            ['key' => 'May need immediate attention and individualized intervention', 'en' => 'May need immediate attention and individualized intervention', 'km' => 'អាចត្រូវការការយកចិត្តទុកដាក់បន្ទាន់ និងអន្តរាគមន៍បុគ្គល'],
            ['key' => 'Consider timeframe', 'en' => 'Consider timeframe', 'km' => 'ពិចារណាពេលវេលា'],
            ['key' => 'Progress between baseline and midline vs. baseline and endline may differ', 'en' => 'Progress between baseline and midline vs. baseline and endline may differ', 'km' => 'វឌ្ឍនភាពរវាងមូលដ្ឋាន និងកណ្តាល ធៀបនឹង មូលដ្ឋាន និងចុងក្រោយ អាចខុសគ្នា'],

            // Actions Section
            ['key' => 'Actions', 'en' => 'Actions', 'km' => 'សកម្មភាព'],
            ['key' => 'Use this progress data to identify students who need additional support, recognize successful teaching methods, and plan targeted interventions.', 'en' => 'Use this progress data to identify students who need additional support, recognize successful teaching methods, and plan targeted interventions.', 'km' => 'ប្រើទិន្នន័យវឌ្ឍនភាពនេះដើម្បីកំណត់អត្តសញ្ញាណសិស្សដែលត្រូវការការគាំទ្របន្ថែម ស្គាល់វិធីសាស្ត្របង្រៀនប្រកបដោយជោគជ័យ និងរៀបចំផែនការអន្តរាគមន៍គោលដៅ។'],
            ['key' => 'Export Chart', 'en' => 'Export Chart', 'km' => 'នាំចេញតារាង'],
            ['key' => 'Print Report', 'en' => 'Print Report', 'km' => 'បោះពុម្ពរបាយការណ៍'],
            ['key' => 'View Overall Performance', 'en' => 'View Overall Performance', 'km' => 'មើលការអនុវត្តទូទៅ'],

            // Chart and Table Note
            ['key' => 'Note', 'en' => 'Note', 'km' => 'ចំណាំ'],
            ['key' => 'This table shows students sorted by their level improvement. Students who moved up multiple levels appear first, followed by those with smaller improvements, then those who maintained their level, and finally those who declined.', 'en' => 'This table shows students sorted by their level improvement. Students who moved up multiple levels appear first, followed by those with smaller improvements, then those who maintained their level, and finally those who declined.', 'km' => 'តារាងនេះបង្ហាញសិស្សដែលតម្រៀបតាមការកែលម្អកម្រិតរបស់ពួកគេ។ សិស្សដែលឡើងកម្រិតច្រើនបង្ហាញមុនបង្អស់ បន្ទាប់មកដោយអ្នកដែលមានការកែលម្អតូច បន្ទាប់មកអ្នកដែលរក្សាកម្រិតរបស់ពួកគេ និងចុងក្រោយអ្នកដែលធ្លាក់ចុះ។'],
            ['key' => 'How Progress is Calculated', 'en' => 'How Progress is Calculated', 'km' => 'របៀបគណនាវឌ្ឍនភាព'],
            ['key' => 'Level Change', 'en' => 'Level Change', 'km' => 'ការផ្លាស់ប្តូរកម្រិត'],
            ['key' => 'Score Change', 'en' => 'Score Change', 'km' => 'ការផ្លាស់ប្តូរពិន្ទុ'],
            ['key' => 'Progress Summary', 'en' => 'Progress Summary', 'km' => 'សង្ខេបវឌ្ឍនភាព'],
            ['key' => 'Students Improved', 'en' => 'Students Improved', 'km' => 'សិស្សបានកែលម្អ'],
            ['key' => 'Students Maintained', 'en' => 'Students Maintained', 'km' => 'សិស្សបានរក្សា'],
            ['key' => 'Students Declined', 'en' => 'Students Declined', 'km' => 'សិស្សបានធ្លាក់ចុះ'],
            ['key' => 'Individual Student Progress', 'en' => 'Individual Student Progress', 'km' => 'វឌ្ឍនភាពសិស្សបុគ្គល'],
            ['key' => 'Student Progress Distribution', 'en' => 'Student Progress Distribution', 'km' => 'ការបែងចែកវឌ្ឍនភាពសិស្ស'],
            ['key' => 'Improved', 'en' => 'Improved', 'km' => 'បានកែលម្អ'],
            ['key' => 'Maintained', 'en' => 'Maintained', 'km' => 'បានរក្សា'],
            ['key' => 'Declined', 'en' => 'Declined', 'km' => 'បានធ្លាក់ចុះ'],

            // Performance Calculation Report Translations
            ['key' => 'Analysis of student performance based on reading levels and mathematical operations capability', 'en' => 'Analysis of student performance based on reading levels and mathematical operations capability', 'km' => 'ការវិភាគការអនុវត្តរបស់សិស្សដោយផ្អែកលើកម្រិតការអាន និងសមត្ថភាពប្រមាណវិធីគណិតវិទ្យា'],
            ['key' => 'Assessment Cycle', 'en' => 'Assessment Cycle', 'km' => 'វដ្តវាយតម្លៃ'],
            ['key' => 'Filter Results', 'en' => 'Filter Results', 'km' => 'លទ្ធផលតម្រង'],
            ['key' => 'Calculation of Performance', 'en' => 'Calculation of Performance', 'km' => 'ការគណនាការអនុវត្ត'],
            ['key' => 'a. Language -', 'en' => 'a. Language -', 'km' => 'ក. ភាសា -'],
            ['key' => 'Letters = Para + Story + Comp 1 + Comp 2', 'en' => 'Letters = Para + Story + Comp 1 + Comp 2', 'km' => 'អ្នកអាន = កថាខណ្ឌ + រឿង + យល់ ១ + យល់ ២'],
            ['key' => 'Beginners = Beginner + Letter', 'en' => 'Beginners = Beginner + Letter', 'km' => 'អ្នកចាប់ផ្តើម = ចាប់ផ្តើម + អក្សរ'],
            ['key' => 'b. Math -', 'en' => 'b. Math -', 'km' => 'ខ. គណិតវិទ្យា -'],
            ['key' => 'Students who can do operations = Subtraction + Division + Word Problem', 'en' => 'Students who can do operations = Subtraction + Division + Word Problem', 'km' => 'សិស្សដែលអាចធ្វើប្រមាណវិធី = ដក + ចែក + បញ្ហាពាក្យ'],
            ['key' => 'Beginners = Beginner + 1-Digit', 'en' => 'Beginners = Beginner + 1-Digit', 'km' => 'អ្នកចាប់ផ្តើម = ចាប់ផ្តើម + ១ តួ'],
            ['key' => 'c. Sample graph', 'en' => 'c. Sample graph', 'km' => 'គ. តារាងគំរូ'],
            ['key' => 'Children who can read at least a simple paragraph', 'en' => 'Children who can read at least a simple paragraph', 'km' => 'កុមារដែលអាចអានយ៉ាងតិចកថាខណ្ឌសាមញ្ញ'],
            ['key' => 'Performance Data - ', 'en' => 'Performance Data - ', 'km' => 'ទិន្នន័យការអនុវត្ត - '],
            ['key' => 'Language Performance', 'en' => 'Language Performance', 'km' => 'ការអនុវត្តភាសា'],
            ['key' => 'Math Performance', 'en' => 'Math Performance', 'km' => 'ការអនុវត្តគណិតវិទ្យា'],
            ['key' => 'Name', 'en' => 'Name', 'km' => 'ឈ្មោះ'],
            ['key' => 'Letters', 'en' => 'Letters', 'km' => 'អ្នកអាន'],
            ['key' => 'Operations', 'en' => 'Operations', 'km' => 'ប្រមាណវិធី'],
            ['key' => 'No Performance Data Available', 'en' => 'No Performance Data Available', 'km' => 'គ្មានទិន្នន័យការអនុវត្តទេ'],
            ['key' => 'No assessment data found for the selected criteria. Please try different filters or ensure assessments have been completed.', 'en' => 'No assessment data found for the selected criteria. Please try different filters or ensure assessments have been completed.', 'km' => 'រកមិនឃើញទិន្នន័យវាយតម្លៃសម្រាប់លក្ខណៈវិនិច្ឆ័យដែលបានជ្រើសរើសទេ។ សូមសាកល្បងតម្រងផ្សេងគ្នា ឬធានាថាការវាយតម្លៃត្រូវបានបញ្ចប់។'],

            // Assessment Chart Labels
            ['key' => 'Khmer Assessment Results', 'en' => 'Khmer Assessment Results', 'km' => 'លទ្ធផលការវាយតម្លៃខ្មែរ'],
            ['key' => 'Math Assessment Results', 'en' => 'Math Assessment Results', 'km' => 'លទ្ធផលការវាយតម្លៃគណិតវិទ្យា'],
            ['key' => 'Reading Level', 'en' => 'Reading Level', 'km' => 'កម្រិតការអាន'],
            ['key' => 'Error loading assessment data', 'en' => 'Error loading assessment data', 'km' => 'កំហុសក្នុងការផ្ទុកទិន្នន័យការវាយតម្លៃ'],

            // Public Assessment Results Page
            ['key' => 'TaRL Project', 'en' => 'TaRL Project', 'km' => 'គម្រោង TaRL'],
            ['key' => 'Assessment Results', 'en' => 'Assessment Results', 'km' => 'លទ្ធផលការវាយតម្លៃ'],
            ['key' => 'PLP', 'en' => 'PLP', 'km' => 'PLP'],
            ['key' => 'Dashboard', 'en' => 'Dashboard', 'km' => 'ផ្ទាំងព័ត៌មាន'],
            ['key' => 'Log in', 'en' => 'Log in', 'km' => 'ចូលប្រព័ន្ធ'],
            ['key' => 'Register', 'en' => 'Register', 'km' => 'ចុះឈ្មោះ'],
            ['key' => 'Total Students Assessed', 'en' => 'Total Students Assessed', 'km' => 'សិស្សសរុបដែលបានវាយតម្លៃ'],

            // Chart JavaScript Translations
            ['key' => 'Student Progress Distribution', 'en' => 'Student Progress Distribution', 'km' => 'ការបែងចែកវឌ្ឍនភាពសិស្ស'],
            ['key' => 'students', 'en' => 'students', 'km' => 'សិស្ស'],
            ['key' => 'Improved', 'en' => 'Improved', 'km' => 'បានកែលម្អ'],
            ['key' => 'Maintained', 'en' => 'Maintained', 'km' => 'បានរក្សា'],
            ['key' => 'Declined', 'en' => 'Declined', 'km' => 'បានធ្លាក់ចុះ'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                ]
            );
        }
    }
}

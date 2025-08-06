<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Validation Group Translations
$validationTranslations = [
    'The :attribute field is required.' => 'វាល :attribute ត្រូវតែបំពេញ។',
    'The :attribute must be a valid email address.' => ':attribute ត្រូវតែជាអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវ។',
    'The :attribute must be at least :min characters.' => ':attribute ត្រូវតែមានយ៉ាងហោចណាស់ :min តួអក្សរ។',
    'The :attribute may not be greater than :max characters.' => ':attribute មិនត្រូវលើសពី :max តួអក្សរទេ។',
    'The :attribute confirmation does not match.' => 'ការបញ្ជាក់ :attribute មិនត្រូវគ្នាទេ។',
    'The :attribute has already been taken.' => ':attribute ត្រូវបានគេយករួចហើយ។',
    'The :attribute must be a number.' => ':attribute ត្រូវតែជាលេខ។',
    'The :attribute must be a file.' => ':attribute ត្រូវតែជាឯកសារ។',
    'The :attribute must be an image.' => ':attribute ត្រូវតែជារូបភាព។',
    'The :attribute must be a date.' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទ។',
    'Please fix the errors below.' => 'សូមកែកំហុសខាងក្រោម។',
    'The :attribute must be a string.' => ':attribute ត្រូវតែជាអក្សរ។',
    'The :attribute must be an integer.' => ':attribute ត្រូវតែជាចំនួនគត់។',
    'The :attribute must be a boolean.' => ':attribute ត្រូវតែជា បាទ/ចាស ឬ ទេ។',
    'The :attribute must be an array.' => ':attribute ត្រូវតែជាអារេ។',
    'The :attribute must be a valid URL.' => ':attribute ត្រូវតែជា URL ត្រឹមត្រូវ។',
    'The :attribute must be a valid date.' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទត្រឹមត្រូវ។',
    'The :attribute must be a valid time.' => ':attribute ត្រូវតែជាពេលវេលាត្រឹមត្រូវ។',
    'The :attribute must be accepted.' => ':attribute ត្រូវតែទទួលយក។',
    'The :attribute must be accepted when :other is :value.' => ':attribute ត្រូវតែទទួលយកនៅពេល :other គឺ :value។',
    'The :attribute must be after :date.' => ':attribute ត្រូវតែក្រោយ :date។',
    'The :attribute must be after or equal to :date.' => ':attribute ត្រូវតែក្រោយ ឬស្មើ :date។',
    'The :attribute must be before :date.' => ':attribute ត្រូវតែមុន :date។',
    'The :attribute must be before or equal to :date.' => ':attribute ត្រូវតែមុន ឬស្មើ :date។',
    'The :attribute must be between :min and :max.' => ':attribute ត្រូវតែនៅចន្លោះ :min និង :max។',
    'The :attribute must be between :min and :max characters.' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max តួអក្សរ។',
    'The :attribute must be between :min and :max digits.' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max ខ្ទង់។',
    'The :attribute must be between :min and :max kilobytes.' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max គីឡូបៃ។',
    'The :attribute must contain :size items.' => ':attribute ត្រូវតែមាន :size ធាតុ។',
    'The :attribute must contain between :min and :max items.' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max ធាតុ។',
    'The :attribute and :other must be different.' => ':attribute និង :other ត្រូវតែខុសគ្នា។',
    'The :attribute must have :digits digits.' => ':attribute ត្រូវតែមាន :digits ខ្ទង់។',
    'The :attribute must have between :min and :max digits.' => ':attribute ត្រូវតែមានចន្លោះ :min និង :max ខ្ទង់។',
    'The :attribute must be a valid email.' => ':attribute ត្រូវតែជាអ៊ីមែលត្រឹមត្រូវ។',
    'The :attribute must end with one of the following: :values.' => ':attribute ត្រូវតែបញ្ចប់ដោយមួយក្នុងចំណោម៖ :values។',
    'The :attribute value :input is not valid.' => 'តម្លៃ :attribute :input មិនត្រឹមត្រូវទេ។',
    'The :attribute must be greater than :value.' => ':attribute ត្រូវតែធំជាង :value។',
    'The :attribute must be greater than or equal to :value.' => ':attribute ត្រូវតែធំជាង ឬស្មើ :value។',
    'The :attribute must be less than :value.' => ':attribute ត្រូវតែតូចជាង :value។',
    'The :attribute must be less than or equal to :value.' => ':attribute ត្រូវតែតូចជាង ឬស្មើ :value។',
    'The :attribute field must be true or false.' => 'វាល :attribute ត្រូវតែជា ពិត ឬ មិនពិត។',
    'The :attribute must be in :format format.' => ':attribute ត្រូវតែនៅក្នុងទម្រង់ :format។',
    'The :attribute field does not exist.' => 'វាល :attribute មិនមានទេ។',
    'The :attribute must contain only letters.' => ':attribute ត្រូវតែមានតែអក្សរប៉ុណ្ណោះ។',
    'The :attribute must contain only letters and numbers.' => ':attribute ត្រូវតែមានតែអក្សរនិងលេខប៉ុណ្ណោះ។',
    'The :attribute must contain only letters, numbers, and dashes.' => ':attribute ត្រូវតែមានតែអក្សរ លេខ និងសញ្ញាដាច់ប៉ុណ្ណោះ។',
    'The :attribute must be lowercase.' => ':attribute ត្រូវតែជាអក្សរតូច។',
    'The :attribute must be uppercase.' => ':attribute ត្រូវតែជាអក្សរធំ។',
    'The :attribute must not be present.' => ':attribute មិនត្រូវមានវត្តមានទេ។',
    'The :attribute field must be present.' => 'វាល :attribute ត្រូវតែមានវត្តមាន។',
    'The :attribute field must be present when :other is :value.' => 'វាល :attribute ត្រូវតែមានវត្តមាននៅពេល :other គឺ :value។',
    'The :attribute field must be present unless :other is :value.' => 'វាល :attribute ត្រូវតែមានវត្តមានលើកលែងតែ :other គឺ :value។',
    'The :attribute field must be present with :values.' => 'វាល :attribute ត្រូវតែមានវត្តមានជាមួយ :values។',
    'The :attribute field must be present with all of :values.' => 'វាល :attribute ត្រូវតែមានវត្តមានជាមួយ :values ទាំងអស់។',
    'The :attribute field must be present without :values.' => 'វាល :attribute ត្រូវតែមានវត្តមានដោយគ្មាន :values។',
    'The :attribute field must be present without all of :values.' => 'វាល :attribute ត្រូវតែមានវត្តមានដោយគ្មាន :values ទាំងអស់។',
    'The :attribute field is required when :other is :value.' => 'វាល :attribute ចាំបាច់នៅពេល :other គឺ :value។',
    'The :attribute field is required unless :other is in :values.' => 'វាល :attribute ចាំបាច់លើកលែងតែ :other ស្ថិតក្នុង :values។',
    'The :attribute field is required with :values.' => 'វាល :attribute ចាំបាច់ជាមួយ :values។',
    'The :attribute field is required with all of :values.' => 'វាល :attribute ចាំបាច់ជាមួយ :values ទាំងអស់។',
    'The :attribute field is required without :values.' => 'វាល :attribute ចាំបាច់ដោយគ្មាន :values។',
    'The :attribute field is required without all of :values.' => 'វាល :attribute ចាំបាច់ដោយគ្មាន :values ទាំងអស់។',
    'The :attribute must be the same as :other.' => ':attribute ត្រូវតែដូចគ្នានឹង :other។',
    'The :attribute must match :other.' => ':attribute ត្រូវតែផ្គូផ្គងនឹង :other។',
    'The :attribute must be :size.' => ':attribute ត្រូវតែមាន :size។',
    'The :attribute must be :size characters.' => ':attribute ត្រូវតែមាន :size តួអក្សរ។',
    'The :attribute must be :size kilobytes.' => ':attribute ត្រូវតែមាន :size គីឡូបៃ។',
    'The :attribute must start with one of the following: :values.' => ':attribute ត្រូវតែចាប់ផ្តើមដោយមួយក្នុងចំណោម៖ :values។',
    'The :attribute failed to upload.' => ':attribute បានបរាជ័យក្នុងការផ្ទុកឡើង។',
    'The :attribute must not be empty.' => ':attribute មិនត្រូវទទេទេ។',
    'The :attribute format is invalid.' => 'ទម្រង់ :attribute មិនត្រឹមត្រូវ។',
    'The :attribute must be unique.' => ':attribute ត្រូវតែមានតែមួយគត់។',
    'The given :attribute has already been used.' => ':attribute ដែលបានផ្តល់ត្រូវបានប្រើរួចហើយ។',
    'The :attribute must exist.' => ':attribute ត្រូវតែមាន។',
    'The selected :attribute is invalid.' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវ។',
    'The :attribute does not exist.' => ':attribute មិនមានទេ។',
    'The :attribute is not a valid :type.' => ':attribute មិនមែនជា :type ត្រឹមត្រូវទេ។',
    'The :attribute must be a valid JSON string.' => ':attribute ត្រូវតែជា JSON string ត្រឹមត្រូវ។',
    'The :attribute must be a multiple of :value.' => ':attribute ត្រូវតែជាពហុគុណនៃ :value។',
    'The :attribute field is prohibited.' => 'វាល :attribute ត្រូវបានហាមឃាត់។',
    'The :attribute field is prohibited when :other is :value.' => 'វាល :attribute ត្រូវបានហាមឃាត់នៅពេល :other គឺ :value។',
    'The :attribute field is prohibited unless :other is in :values.' => 'វាល :attribute ត្រូវបានហាមឃាត់លើកលែងតែ :other ស្ថិតក្នុង :values។',
    'The :attribute must be one of the following types: :values.' => ':attribute ត្រូវតែជាប្រភេទមួយក្នុងចំណោម៖ :values។',
    'The :attribute must not start with one of the following: :values.' => ':attribute មិនត្រូវចាប់ផ្តើមដោយមួយក្នុងចំណោម៖ :values។',
    'The :attribute must not end with one of the following: :values.' => ':attribute មិនត្រូវបញ្ចប់ដោយមួយក្នុងចំណោម៖ :values។',
    'The :attribute field must be declined.' => 'វាល :attribute ត្រូវតែបដិសេធ។',
    'The :attribute field must be declined when :other is :value.' => 'វាល :attribute ត្រូវតែបដិសេធនៅពេល :other គឺ :value។',
    'The password is incorrect.' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',
    'The provided password is incorrect.' => 'ពាក្យសម្ងាត់ដែលបានផ្តល់មិនត្រឹមត្រូវ។',
    'The provided password was incorrect.' => 'ពាក្យសម្ងាត់ដែលបានផ្តល់មិនត្រឹមត្រូវ។',
    'The provided two factor authentication code was invalid.' => 'លេខកូដផ្ទៀងផ្ទាត់ពីរកត្តាដែលបានផ្តល់មិនត្រឹមត្រូវ។',
    'The provided two factor recovery code was invalid.' => 'លេខកូដស្តារពីរកត្តាដែលបានផ្តល់មិនត្រឹមត្រូវ។',
    'This password has been compromised in a data breach.' => 'ពាក្យសម្ងាត់នេះត្រូវបានគេលួចក្នុងការលេចធ្លាយទិន្នន័យ។',
    'The :attribute contains invalid characters.' => ':attribute មានតួអក្សរមិនត្រឹមត្រូវ។',
    'The :attribute is too short.' => ':attribute ខ្លីពេក។',
    'The :attribute is too long.' => ':attribute វែងពេក។',
    'The :attribute is too weak.' => ':attribute ខ្សោយពេក។',
    'Please choose a stronger :attribute.' => 'សូមជ្រើសរើស :attribute ដែលរឹងមាំជាង។',
];

echo "Updating validation translations...\n";
$count = 0;

foreach ($validationTranslations as $en => $km) {
    Translation::where('key', $en)
        ->where('group', 'validation')
        ->update(['km' => $km]);
    $count++;
}

echo "Updated {$count} validation translations.\n";

// Clear cache
Translation::clearCache();
echo "Cache cleared.\n";

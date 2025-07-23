<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bulk Import Users') }}
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                {{ __('Back to Users') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Instructions and Download Template -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Instructions') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-800 mb-2">{{ __('How to Import:') }}</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>{{ __('Download the Excel template below') }}</li>
                                    <li>{{ __('Fill in the user data following the format') }}</li>
                                    <li>{{ __('Upload the completed Excel file') }}</li>
                                    <li>{{ __('Review and confirm the import') }}</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800 mb-2">{{ __('Field Requirements:') }}</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li><strong>{{ __('Required:') }}</strong> Name, Email, Password, Role</li>
                                    <li><strong>{{ __('Role values:') }}</strong> admin, teacher, mentor, viewer</li>
                                    <li><strong>{{ __('Sex values:') }}</strong> male, female</li>
                                    <li><strong>{{ __('School:') }}</strong> Required for teachers and mentors</li>
                                    <li><strong>{{ __('Password:') }}</strong> Minimum 8 characters</li>
                                    <li><strong>{{ __('Is Active:') }}</strong> yes/no (default: yes)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template Button -->
                    <div class="mb-6 flex gap-4">
                        <a href="{{ route('users.download-template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            {{ __('Download Excel Template') }}
                        </a>
                    </div>

                    <!-- File Upload Form -->
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Select Excel File') }}
                            </label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">{{ __('Accepted formats: Excel (.xlsx, .xls) or CSV (.csv)') }}</p>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" id="previewBtn" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('Preview Data') }}
                            </button>
                            <button type="submit" id="importBtn" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700" disabled>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                {{ __('Import Users') }}
                            </button>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div id="previewSection" class="mt-6 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Data Preview') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="previewTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('School') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Sex') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Holding Classes') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="previewBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>{{ __('Total rows to import:') }} <span id="rowCount">0</span></p>
                        </div>
                    </div>

                    <!-- Validation Messages -->
                    <div id="validationMessages" class="mt-6 hidden">
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ __('Validation Errors') }}</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1" id="errorList"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        let importData = [];
        const schools = @json(\App\Models\School::orderBy('school_name')->pluck('school_name', 'id')->toArray());
        
        // Translations for JavaScript
        const translations = {
            rowNameRequired: {!! json_encode(__('Row :rowNum: Name is required')) !!},
            rowEmailRequired: {!! json_encode(__('Row :rowNum: Email is required')) !!},
            rowInvalidEmail: {!! json_encode(__('Row :rowNum: Invalid email format')) !!},
            rowPasswordRequired: {!! json_encode(__('Row :rowNum: Password is required')) !!},
            rowPasswordLength: {!! json_encode(__('Row :rowNum: Password must be at least 8 characters')) !!},
            rowRoleRequired: {!! json_encode(__('Row :rowNum: Role is required')) !!},
            rowInvalidRole: {!! json_encode(__('Row :rowNum: Invalid role. Must be admin, teacher, mentor, or viewer')) !!},
            rowSchoolRequired: {!! json_encode(__('Row :rowNum: School is required for :role')) !!},
            rowInvalidSex: {!! json_encode(__('Row :rowNum: Sex must be male or female')) !!},
            active: {!! json_encode(__('Active')) !!},
            inactive: {!! json_encode(__('Inactive')) !!}
        };

        document.getElementById('previewBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('file');
            if (!fileInput.files.length) {
                alert('{{ __("Please select a file first") }}');
                return;
            }

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet);

                    processAndPreviewData(jsonData);
                } catch (error) {
                    alert('{{ __("Error reading file. Please ensure it\'s a valid Excel file.") }}');
                    console.error(error);
                }
            };

            reader.readAsArrayBuffer(file);
        });

        function processAndPreviewData(data) {
            importData = [];
            const previewBody = document.getElementById('previewBody');
            const errors = [];
            previewBody.innerHTML = '';

            data.forEach((row, index) => {
                // Map Excel columns to database fields
                const user = {
                    name: row['Name'] || row['name'] || '',
                    email: row['Email'] || row['email'] || '',
                    password: row['Password'] || row['password'] || '',
                    role: (row['Role'] || row['role'] || '').toLowerCase(),
                    school_name: row['School'] || row['school'] || '',
                    sex: (row['Sex'] || row['sex'] || '').toLowerCase(),
                    phone: row['Phone'] || row['phone'] || '',
                    telephone: row['Telephone'] || row['telephone'] || '',
                    holding_classes: row['Holding Classes'] || row['holding_classes'] || '',
                    is_active: row['Is Active'] || row['is_active'] || 'yes'
                };

                // Find school ID
                let schoolId = null;
                if (user.school_name) {
                    for (let [id, name] of Object.entries(schools)) {
                        if (name.toLowerCase() === user.school_name.toLowerCase()) {
                            schoolId = id;
                            break;
                        }
                    }
                }

                // Validate row
                const rowNum = index + 1;
                let hasError = false;

                if (!user.name) {
                    errors.push(translations.rowNameRequired.replace(':rowNum', rowNum));
                    hasError = true;
                }
                if (!user.email) {
                    errors.push(translations.rowEmailRequired.replace(':rowNum', rowNum));
                    hasError = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(user.email)) {
                    errors.push(translations.rowInvalidEmail.replace(':rowNum', rowNum));
                    hasError = true;
                }
                if (!user.password) {
                    errors.push(translations.rowPasswordRequired.replace(':rowNum', rowNum));
                    hasError = true;
                } else if (user.password.length < 8) {
                    errors.push(translations.rowPasswordLength.replace(':rowNum', rowNum));
                    hasError = true;
                }
                if (!user.role) {
                    errors.push(translations.rowRoleRequired.replace(':rowNum', rowNum));
                    hasError = true;
                } else if (!['admin', 'teacher', 'mentor', 'viewer'].includes(user.role)) {
                    errors.push(translations.rowInvalidRole.replace(':rowNum', rowNum));
                    hasError = true;
                }
                if ((user.role === 'teacher' || user.role === 'mentor') && !schoolId) {
                    errors.push(translations.rowSchoolRequired.replace(':rowNum', rowNum).replace(':role', user.role));
                    hasError = true;
                }
                if (user.sex && !['male', 'female'].includes(user.sex)) {
                    errors.push(translations.rowInvalidSex.replace(':rowNum', rowNum));
                    hasError = true;
                }

                // Convert is_active
                user.is_active = ['yes', 'true', '1', 1, true].includes(String(user.is_active).toLowerCase());

                // Add to import data
                importData.push({
                    ...user,
                    school_id: schoolId,
                    hasError: hasError
                });

                // Add to preview table
                const tr = document.createElement('tr');
                tr.className = hasError ? 'bg-red-50' : '';
                tr.innerHTML = `
                    <td class="px-3 py-2 text-sm">${user.name}</td>
                    <td class="px-3 py-2 text-sm">${user.email}</td>
                    <td class="px-3 py-2 text-sm">${user.role}</td>
                    <td class="px-3 py-2 text-sm">${user.school_name}</td>
                    <td class="px-3 py-2 text-sm">${user.sex || '-'}</td>
                    <td class="px-3 py-2 text-sm">${user.phone || '-'}</td>
                    <td class="px-3 py-2 text-sm">${user.holding_classes || '-'}</td>
                    <td class="px-3 py-2 text-sm">
                        <span class="inline-flex px-2 text-xs font-semibold rounded-full ${user.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${user.is_active ? translations.active : translations.inactive}
                        </span>
                    </td>
                `;
                previewBody.appendChild(tr);
            });

            // Update counts and show preview
            document.getElementById('rowCount').textContent = importData.length;
            document.getElementById('previewSection').classList.remove('hidden');

            // Show validation errors
            if (errors.length > 0) {
                document.getElementById('errorList').innerHTML = errors.map(e => `<li>${e}</li>`).join('');
                document.getElementById('validationMessages').classList.remove('hidden');
                document.getElementById('importBtn').disabled = true;
            } else {
                document.getElementById('validationMessages').classList.add('hidden');
                document.getElementById('importBtn').disabled = false;
            }
        }

        document.getElementById('importForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (importData.length === 0) {
                alert('{{ __("No data to import") }}');
                return;
            }

            // Filter out rows with errors
            const validData = importData.filter(user => !user.hasError);
            if (validData.length === 0) {
                alert('{{ __("No valid data to import. Please fix the errors first.") }}');
                return;
            }

            // Confirm import
            if (!confirm(`{{ __("Are you sure you want to import :count users?") }}`.replace(':count', validData.length))) {
                return;
            }

            // Show loading
            const importBtn = document.getElementById('importBtn');
            importBtn.disabled = true;
            importBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __("Importing...") }}';

            // Send AJAX request
            fetch('{{ route("users.bulk-import-enhanced") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ users: validData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("users.index") }}';
                } else {
                    alert(data.message || '{{ __("Import failed") }}');
                    importBtn.disabled = false;
                    importBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg> {{ __("Import Users") }}';
                }
            })
            .catch(error => {
                alert('{{ __("An error occurred during import") }}');
                console.error(error);
                importBtn.disabled = false;
                importBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg> {{ __("Import Users") }}';
            });
        });
    </script>
    @endpush
</x-app-layout>
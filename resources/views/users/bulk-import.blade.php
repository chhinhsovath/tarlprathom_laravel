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

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Instructions') }}</h3>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>{{ __('Copy data from your Excel file and paste it into the table below') }}</li>
                            <li>{{ __('Make sure the columns match the headers in the table') }}</li>
                            <li>{{ __('Required fields: Name, Email, Password, Role') }}</li>
                            <li>{{ __('Role values: admin, teacher, mentor') }}</li>
                            <li>{{ __('School is required for teachers and mentors') }}</li>
                            <li>{{ __('Password must be at least 8 characters') }}</li>
                        </ul>
                    </div>

                    <div class="mb-4 flex gap-4">
                        <button id="clearData" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <i class="fas fa-eraser mr-2"></i> {{ __('Clear Data') }}
                        </button>
                        <button id="addRow" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> {{ __('Add Row') }}
                        </button>
                        <button id="validateData" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                            <i class="fas fa-check-circle mr-2"></i> {{ __('Validate Data') }}
                        </button>
                        <button id="importData" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700" disabled>
                            <i class="fas fa-upload mr-2"></i> {{ __('Import Users') }}
                        </button>
                    </div>

                    <div id="validationMessages" class="mb-4 hidden">
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ __('Validation Errors') }}</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside" id="errorList"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="handsontable-container" style="height: 400px; overflow: hidden;"></div>

                    <div class="mt-4 text-sm text-gray-600">
                        <p>{{ __('Total rows') }}: <span id="rowCount">0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get schools list for dropdown
            let schools = @json(\App\Models\School::orderBy('sclName')->pluck('sclName', 'sclAutoID')->toArray());
            let schoolNames = Object.values(schools);
            schoolNames.unshift(''); // Add empty option for admin users
            
            const container = document.getElementById('handsontable-container');
            
            const data = [
                ['', '', '', '', '', '']
            ];

            const hot = new Handsontable(container, {
                data: data,
                colHeaders: [
                    '{{ __("Name") }} *',
                    '{{ __("Email") }} *',
                    '{{ __("Password") }} *',
                    '{{ __("Role") }} *',
                    '{{ __("School") }}',
                    '{{ __("Phone Number") }}'
                ],
                columns: [
                    { type: 'text' }, // Name
                    { type: 'text' }, // Email
                    { type: 'text' }, // Password
                    { 
                        type: 'dropdown',
                        source: ['admin', 'teacher', 'mentor']
                    }, // Role
                    {
                        type: 'dropdown',
                        source: schoolNames
                    }, // School
                    { type: 'text' }, // Phone Number
                ],
                stretchH: 'all',
                width: '100%',
                height: 400,
                contextMenu: true,
                manualRowMove: true,
                manualColumnMove: false,
                manualRowResize: true,
                manualColumnResize: true,
                rowHeaders: true,
                autoWrapRow: true,
                autoWrapCol: true,
                licenseKey: 'non-commercial-and-evaluation',
                afterChange: function(changes, source) {
                    updateRowCount();
                }
            });

            function updateRowCount() {
                const data = hot.getData();
                const nonEmptyRows = data.filter(row => row.some(cell => cell !== null && cell !== ''));
                document.getElementById('rowCount').textContent = nonEmptyRows.length;
            }

            // Clear data
            document.getElementById('clearData').addEventListener('click', function() {
                if (confirm('{{ __("Are you sure you want to clear all data?") }}')) {
                    hot.loadData([['', '', '', '', '', '']]);
                    document.getElementById('validationMessages').classList.add('hidden');
                    document.getElementById('importData').disabled = true;
                }
            });

            // Add row
            document.getElementById('addRow').addEventListener('click', function() {
                hot.alter('insert_row_below', hot.countRows() - 1);
            });

            // Validate data
            document.getElementById('validateData').addEventListener('click', function() {
                const data = hot.getData();
                const errors = [];
                let hasValidData = false;

                data.forEach((row, index) => {
                    // Skip empty rows
                    if (!row.some(cell => cell !== null && cell !== '')) {
                        return;
                    }

                    hasValidData = true;
                    const rowNum = index + 1;

                    // Validate required fields
                    if (!row[0] || row[0].trim() === '') {
                        errors.push(`Row ${rowNum}: Name is required`);
                    }
                    if (!row[1] || row[1].trim() === '') {
                        errors.push(`Row ${rowNum}: Email is required`);
                    }
                    if (!row[2] || row[2].trim() === '') {
                        errors.push(`Row ${rowNum}: Password is required`);
                    }
                    if (!row[3] || row[3].trim() === '') {
                        errors.push(`Row ${rowNum}: Role is required`);
                    }

                    // Validate email format
                    if (row[1] && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(row[1])) {
                        errors.push(`Row ${rowNum}: Invalid email format`);
                    }

                    // Validate password length
                    if (row[2] && row[2].length < 8) {
                        errors.push(`Row ${rowNum}: Password must be at least 8 characters`);
                    }

                    // Validate role
                    if (row[3] && !['admin', 'teacher', 'mentor'].includes(row[3].toLowerCase())) {
                        errors.push(`Row ${rowNum}: Role must be 'admin', 'teacher', or 'mentor'`);
                    }

                    // Validate school requirement for non-admin roles
                    if (row[3] && row[3] !== 'admin' && (!row[4] || row[4].trim() === '')) {
                        errors.push(`Row ${rowNum}: School is required for ${row[3]} role`);
                    }

                    // Validate school exists
                    if (row[4] && row[4].trim() !== '' && !schoolNames.includes(row[4])) {
                        errors.push(`Row ${rowNum}: School '${row[4]}' not found`);
                    }
                });

                const validationDiv = document.getElementById('validationMessages');
                const errorList = document.getElementById('errorList');

                if (errors.length > 0) {
                    errorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                    validationDiv.classList.remove('hidden');
                    document.getElementById('importData').disabled = true;
                } else if (!hasValidData) {
                    errorList.innerHTML = '<li>{{ __("No data to import") }}</li>';
                    validationDiv.classList.remove('hidden');
                    document.getElementById('importData').disabled = true;
                } else {
                    validationDiv.classList.add('hidden');
                    document.getElementById('importData').disabled = false;
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Validation Passed") }}',
                        text: '{{ __("All data is valid and ready to import") }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

            // Import data
            document.getElementById('importData').addEventListener('click', function() {
                const data = hot.getData();
                const users = [];

                data.forEach(row => {
                    // Skip empty rows
                    if (!row.some(cell => cell !== null && cell !== '')) {
                        return;
                    }

                    // Find school ID
                    let schoolId = null;
                    if (row[4] && row[4].trim() !== '') {
                        for (let [id, name] of Object.entries(schools)) {
                            if (name === row[4]) {
                                schoolId = id;
                                break;
                            }
                        }
                    }

                    users.push({
                        name: row[0],
                        email: row[1],
                        password: row[2],
                        role: row[3].toLowerCase(),
                        school_id: schoolId,
                        phone_number: row[5] || null
                    });
                });

                if (users.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("No Data") }}',
                        text: '{{ __("No valid data to import") }}'
                    });
                    return;
                }

                // Show loading
                showLoading('{{ __("Importing users...") }}');

                // Send AJAX request
                $.ajax({
                    url: '{{ route("users.bulk-import") }}',
                    method: 'POST',
                    data: {
                        users: users,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        hideLoading();
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __("Success") }}',
                            text: response.message,
                            confirmButtonText: '{{ __("View Users") }}'
                        }).then(() => {
                            window.location.href = '{{ route("users.index") }}';
                        });
                    },
                    error: function(xhr) {
                        hideLoading();
                        let message = '{{ __("An error occurred while importing users") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("Import Failed") }}',
                            text: message
                        });
                    }
                });
            });

            // Initialize row count
            updateRowCount();
        });
    </script>
    @endpush
</x-app-layout>
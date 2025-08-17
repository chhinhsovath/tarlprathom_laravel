<div class="max-w-4xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">នាំចូលសិស្សពីឯកសារ CSV</h2>
            
            <!-- Download Template -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-700 mb-2">ទាញយកគំរូ CSV គំរូដើម្បីមើលទម្រង់ដែលរំពឹងទុក:</p>
                <button wire:click="downloadTemplate" class="text-sm text-blue-600 hover:text-blue-800 underline">
                    ទាញយកគំរូ CSV
                </button>
            </div>

            <form wire:submit.prevent="import">
                <!-- School Selection (Admin only) -->
                @if(auth()->user()->role === 'admin')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ជ្រើសរើសសាលា</label>
                    <select wire:model="school_id" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- ជ្រើសរើសសាលា --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                    @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- File Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ឯកសារ CSV</label>
                    <input type="file" wire:model="csvFile" accept=".csv,.txt" class="w-full">
                    @error('csvFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <div wire:loading wire:target="csvFile" class="text-sm text-gray-500 mt-2">
                        កំពុងដំណើរការឯកសារ...
                    </div>
                </div>

                <!-- Field Mapping -->
                @if($showMapping)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium mb-3">ផ្គូផ្គងជួរដេញ CSV ទៅវាលសិស្ស</h3>
                    
                    <!-- Preview -->
                    @if(count($preview) > 0)
                    <div class="mb-4 overflow-x-auto">
                        <p class="text-sm text-gray-600 mb-2">មើលជាមុន (៥ ជួរដំបូង):</p>
                        <table class="min-w-full text-sm border">
                            <thead>
                                <tr class="bg-gray-100">
                                    @foreach($headers as $header)
                                    <th class="border px-2 py-1">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview as $row)
                                <tr>
                                    @foreach($row as $cell)
                                    <td class="border px-2 py-1">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Mapping Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ជួរដេញឈ្មោះ</label>
                            <select wire:model="mapping.name" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">-- ជ្រើសរើសជួរដេញ --</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            @error('mapping.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ជួរដេញភេទ</label>
                            <select wire:model="mapping.sex" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">-- ជ្រើសរើសជួរដេញ --</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            @error('mapping.sex') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ជួរដេញអាយុ</label>
                            <select wire:model="mapping.age" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">-- ជ្រើសរើសជួរដេញ --</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            @error('mapping.age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ជួរដេញថ្នាក់/កម្រិត</label>
                            <select wire:model="mapping.class" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">-- ជ្រើសរើសជួរដេញ --</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            @error('mapping.class') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Import Errors -->
                @if(count($importErrors) > 0)
                <div class="mb-4 p-4 bg-red-50 rounded-lg">
                    <h4 class="text-sm font-medium text-red-800 mb-2">កំហុសក្នុងការនាំចូល:</h4>
                    <ul class="text-sm text-red-700 list-disc list-inside max-h-40 overflow-y-auto">
                        @foreach($importErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            @if(!$showMapping || !$school_id) disabled @endif
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        នាំចូលសិស្ស
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
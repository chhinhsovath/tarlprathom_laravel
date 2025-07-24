<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assessment History') }}: {{ $student->name }}
            </h2>
            <a href="{{ route('students.show', $student) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Student') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Student Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('Name') }}:</span> 
                            <span class="text-lg">{{ $student->name }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('Student ID') }}:</span> 
                            {{ $student->student_id }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('Age') }}:</span> 
                            {{ $student->age ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('Gender') }}:</span> 
                            {{ $student->gender ? __(ucfirst($student->gender)) : 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('Grade') }}:</span> 
                            {{ $student->grade ? __('Grade') . ' ' . $student->grade : 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">{{ __('School') }}:</span> 
                            {{ $student->school ? $student->school->school_name : 'N/A' }}
                        </div>
                        @if($student->teacher)
                        <div class="md:col-span-2 lg:col-span-3">
                            <span class="font-semibold text-gray-600">{{ __('Teacher') }}:</span> 
                            {{ $student->teacher->name }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assessment History by Subject and Cycle -->
            <div class="space-y-6">
                @foreach(['khmer', 'math'] as $subject)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">{{ __($subject == 'khmer' ? 'Khmer' : 'Mathematics') }}</h3>
                            
                            <div class="space-y-4">
                                @foreach(['baseline', 'midline', 'endline'] as $cycle)
                                    @php
                                        $key = $subject . '_' . $cycle;
                                        $currentAssessment = $currentAssessments->get($key);
                                        $historyItems = $histories->get($key, collect());
                                    @endphp
                                    
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-semibold text-md mb-2">{{ __(ucfirst($cycle)) }}</h4>
                                        
                                        @if($currentAssessment)
                                            <!-- Current Assessment -->
                                            <div class="bg-blue-50 rounded p-3 mb-3">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <span class="font-semibold">{{ __('Current Level') }}:</span> 
                                                        <span class="text-lg">{{ $currentAssessment->level }}</span>
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        {{ __('Assessed on') }}: {{ $currentAssessment->assessed_at->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($historyItems && $historyItems->count() > 0)
                                            <!-- History Table -->
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 mt-2">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date/Time') }}</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Level') }}</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Change') }}</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Action') }}</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Updated By') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach($historyItems as $history)
                                                            <tr class="{{ $history->action == 'created' ? 'bg-green-50' : '' }}">
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                                    {{ $history->created_at->format('d/m/Y H:i') }}
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                                                    {{ $history->level }}
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                                    @if($history->level_change)
                                                                        <span class="text-gray-600">{{ $history->level_change }}</span>
                                                                    @else
                                                                        <span class="text-gray-400">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                        {{ $history->action == 'created' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                        {{ __($history->action == 'created' ? 'Created' : 'Updated') }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                                    {{ $history->updatedBy ? $history->updatedBy->name : 'System' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-gray-500 text-sm">{{ __('No assessment history for this cycle') }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
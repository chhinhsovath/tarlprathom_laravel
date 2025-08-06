<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="w-full sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <!-- Filters -->
                <form method="GET" action="{{ route('assessment-management.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">{{ __('Province') }}</label>
                            <select name="province" id="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Provinces') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>
                                        {{ $province }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700">{{ __('District') }}</label>
                            <select name="district" id="district" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Districts') }}</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>
                                        {{ $district }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="cluster" class="block text-sm font-medium text-gray-700">{{ __('Cluster') }}</label>
                            <select name="cluster" id="cluster" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Clusters') }}</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster }}" {{ request('cluster') == $cluster ? 'selected' : '' }}>
                                        {{ $cluster }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700">{{ __('School') }}</label>
                            <select name="school_id" id="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700">{{ __('Teacher') }}</label>
                            <select name="teacher_id" id="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Teachers') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700">{{ __('Assessment Cycle') }}</label>
                            <select name="cycle" id="cycle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Cycles') }}</option>
                                <option value="baseline" {{ request('cycle') == 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                <option value="midline" {{ request('cycle') == 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                <option value="endline" {{ request('cycle') == 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">{{ __('Subject') }}</label>
                            <select name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Subjects') }}</option>
                                <option value="language" {{ request('subject') == 'language' ? 'selected' : '' }}>{{ __('Language') }}</option>
                                <option value="numeracy" {{ request('subject') == 'numeracy' ? 'selected' : '' }}>{{ __('Numeracy') }}</option>
                            </select>
                        </div>

                        @if($hasLockColumns ?? false)
                        <div>
                            <label for="lock_status" class="block text-sm font-medium text-gray-700">{{ __('Lock Status') }}</label>
                            <select name="lock_status" id="lock_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="locked" {{ request('lock_status') == 'locked' ? 'selected' : '' }}>{{ __('Locked') }}</option>
                                <option value="unlocked" {{ request('lock_status') == 'unlocked' ? 'selected' : '' }}>{{ __('Unlocked') }}</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('assessment-management.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                </form>

                <!-- Bulk Actions -->
                <form id="bulk-action-form" method="POST" class="mb-4">
                    @csrf
                    @if($hasLockColumns ?? false)
                    <div class="flex gap-2">
                        <button type="submit" formaction="{{ route('assessment-management.bulk-lock') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Lock Selected') }}
                        </button>
                        <button type="submit" formaction="{{ route('assessment-management.bulk-unlock') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Unlock Selected') }}
                        </button>
                    </div>
                    @endif

                    <!-- Assessment Table -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Student') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Teacher') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Cycle') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Subject') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Level') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Score') }}
                                    </th>
                                    @if($hasLockColumns ?? false)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Locked By') }}
                                    </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($assessments as $assessment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="assessment_ids[]" value="{{ $assessment->id }}" class="assessment-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $assessment->student->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ __('Grade') }}: {{ $assessment->student->grade }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $assessment->student->school->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $assessment->student->school->district }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assessment->student->teacher->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($assessment->cycle == 'baseline') bg-blue-100 text-blue-800
                                                @elseif($assessment->cycle == 'midline') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($assessment->cycle) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($assessment->subject) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assessment->level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assessment->score }}
                                        </td>
                                        @if($hasLockColumns ?? false)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($assessment->is_locked ?? false)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ __('Locked') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ __('Unlocked') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if(($assessment->is_locked ?? false) && $assessment->lockedBy)
                                                {{ $assessment->lockedBy->name }}
                                                <div class="text-xs text-gray-500">
                                                    {{ $assessment->locked_at->format('Y-m-d H:i') }}
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($hasLockColumns ?? false)
                                                @if($assessment->is_locked ?? false)
                                                    <form action="{{ route('assessment-management.unlock', $assessment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">{{ __('Unlock') }}</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('assessment-management.lock', $assessment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">{{ __('Lock') }}</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ ($hasLockColumns ?? false) ? '11' : '9' }}" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('No assessments found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $assessments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.assessment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
</x-app-layout>
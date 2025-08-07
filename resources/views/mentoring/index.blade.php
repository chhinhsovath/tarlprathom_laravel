<x-app-layout>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Action Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('mentoring.Mentoring Visits') }}</h3>
                        @if(in_array(auth()->user()->role, ['admin', 'mentor']))
                        <a href="{{ route('mentoring.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            {{ __('mentoring.Log Visit') }}
                        </a>
                        @endif
                    </div>

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('mentoring.index') }}" class="mb-6">
                        <div class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="{{ __('mentoring.Search by teacher, mentor, school or notes...') }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @if(auth()->user()->isAdmin() || auth()->user()->isViewer())
                            <div>
                                <select name="school_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('mentoring.All Schools') }}</option>
                                    @foreach(App\Models\School::orderBy('name')->get() as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                                    placeholder="{{ __('mentoring.From Date') }}" 
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                                    placeholder="{{ __('mentoring.To Date') }}" 
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('mentoring.Search') }}
                                </button>
                                @if(request('search') || request('school_id') || request('from_date') || request('to_date'))
                                    <a href="{{ route('mentoring.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('mentoring.Clear') }}
                                    </a>
                                @endif
                                <a href="{{ route('mentoring.export') }}?{{ request()->getQueryString() }}" class="export-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ __('mentoring.Export to Excel') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Visits Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="visit_date" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('mentoring.Date') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="school_id" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('mentoring.School') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="teacher_id" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('mentoring.Teacher') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="mentor_id" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('mentoring.Mentor') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="score" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('mentoring.Score') }}
                                        </x-sortable-header>
                                    </th>
                                    @if(\Schema::hasColumn('mentoring_visits', 'is_locked'))
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('mentoring.Status') }}
                                    </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('mentoring.Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($mentoringVisits as $visit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $visit->visit_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $visit->school->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $visit->teacher->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $visit->mentor->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($visit->score >= 80) bg-green-100 text-green-800
                                            @elseif($visit->score >= 60) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $visit->score }}%
                                        </span>
                                    </td>
                                    @if(\Schema::hasColumn('mentoring_visits', 'is_locked'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visit->is_locked ?? false)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ __('mentoring.Locked') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('mentoring.Active') }}
                                            </span>
                                        @endif
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('mentoring.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('mentoring.View') }}
                                        </a>
                                        @if((auth()->user()->isAdmin() || $visit->mentor_id == auth()->user()->id) && (!($visit->is_locked ?? false) || auth()->user()->isAdmin()))
                                            <span class="text-gray-300 mx-1">|</span>
                                            <a href="{{ route('mentoring.edit', $visit) }}" class="text-yellow-600 hover:text-yellow-900">
                                                {{ __('mentoring.Edit') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ \Schema::hasColumn('mentoring_visits', 'is_locked') ? '7' : '6' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('mentoring.No mentoring visits found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($mentoringVisits->hasPages())
                    <div class="mt-4">
                        {{ $mentoringVisits->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
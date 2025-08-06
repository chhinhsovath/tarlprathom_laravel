<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- User Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <!-- Photo Section -->
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full overflow-hidden shadow-md">
                                    <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- User Details -->
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $user->email }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->role == 'coordinator' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $user->role == 'mentor' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role == 'teacher' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role == 'viewer' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @if($user->school)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('School') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->school->name }}</p>
                                    </div>
                                @endif
                                
                                @if($user->sex)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Gender') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($user->sex) }}</p>
                                    </div>
                                @endif
                                
                                @if($user->phone)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->phone }}</p>
                                    </div>
                                @endif
                                
                                @if($user->province)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Province') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->province }}</p>
                                    </div>
                                @endif
                                
                                @if($user->district)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('District') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->district }}</p>
                                    </div>
                                @endif
                                
                                @if($user->commune)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Commune') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->commune }}</p>
                                    </div>
                                @endif
                                
                                @if($user->village)
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Village') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->village }}</p>
                                    </div>
                                @endif
                                
                                @if($user->holding_classes)
                                    <div class="sm:col-span-2">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Holding Classes') }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->holding_classes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Account Information') }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Created At') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Last Updated') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        
                        @if($user->email_verified_at)
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Email Verified') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $user->email_verified_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Schools (for Mentors) -->
            @if($user->role === 'mentor')
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mt-6">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Assigned Schools') }}</h3>
                        
                        @if($user->assignedSchools->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($user->assignedSchools as $school)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <h4 class="font-medium text-gray-900">{{ $school->name }}</h4>
                                        @if($school->province)
                                            <p class="text-sm text-gray-600">{{ $school->province }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('No schools assigned yet.') }}</p>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('users.edit', $user) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit User') }}
                </a>

                @if(auth()->user()->role === 'admin' && $user->role === 'mentor')
                    <a href="{{ route('users.assign-schools', $user) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Assign Schools') }}
                    </a>
                @endif
                
                @if($user->id !== auth()->id())
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete User') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
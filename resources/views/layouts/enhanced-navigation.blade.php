<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800 hover:text-gray-900 transition-colors">
                        {{ trans_db('tarl_project') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- External Link -->
                    <a href="https://plp.moeys.gov.kh" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                       target="_blank">
                        {{ trans_db('plp') }}
                        <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    @auth
                        @unless(auth()->user()->isCoordinator())
                            <!-- Dashboard -->
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <!-- Enhanced Analytics Dashboard -->
                            <x-nav-link :href="route('reports.dashboard')" :active="request()->routeIs('reports.dashboard')">
                                {{ __('Analytics Dashboard') }}
                            </x-nav-link>
                        @endunless

                        @if(in_array(auth()->user()->role, ['admin', 'teacher', 'mentor', 'viewer']))
                            <!-- Assessments -->
                            <x-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                                {{ trans_db('assessments') }}
                            </x-nav-link>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                            <!-- Students -->
                            <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                                {{ trans_db('students') }}
                            </x-nav-link>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'mentor']))
                            <!-- Mentoring -->
                            <x-nav-link :href="route('mentoring.index')" :active="request()->routeIs('mentoring.*')">
                                {{ trans_db('mentoring') }}
                            </x-nav-link>
                        @endif

                        <!-- Reporting Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                {{ __('Reports') }}
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1" role="menu">
                                    <a href="{{ route('reports.dashboard') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ __('Analytics Dashboard') }}
                                    </a>
                                    <a href="{{ route('reports.assessment-analysis') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ __('Assessment Analysis Report') }}
                                    </a>
                                    <a href="{{ route('reports.attendance') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ __('Attendance Report') }}
                                    </a>
                                    <a href="{{ route('reports.intervention') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ __('Intervention Report') }}
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="{{ route('reports.index') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ trans_db('reports') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->isCoordinator())
                            <!-- Coordinator Workspace -->
                            <x-nav-link :href="route('coordinator.workspace')" :active="request()->routeIs('coordinator.*')">
                                {{ trans_db('workspace') }}
                            </x-nav-link>
                        @endif

                        @if(in_array(auth()->user()->role, ['admin']))
                            <!-- Administration Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ trans_db('administration') }}
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1" role="menu">
                                        <a href="{{ route('users.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('users') }}
                                        </a>
                                        <a href="{{ route('schools.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('schools') }}
                                        </a>
                                        <a href="{{ route('classes.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('classes') }}
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('settings.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('settings') }}
                                        </a>
                                        <a href="{{ route('resources.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('resources') }}
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('administration.index') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ trans_db('administration') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Help -->
                        <x-nav-link :href="route('help.index')" :active="request()->routeIs('help.*')">
                            {{ trans_db('help') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown and Language Switcher -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Language Switcher -->
                <div class="relative mr-4" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center text-sm bg-white rounded-md border border-gray-300 px-3 py-2 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        {{ app()->getLocale() == 'km' ? 'ខ្មែរ' : 'English' }}
                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1" role="menu">
                            <a href="{{ route('language.switch', 'km') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'km' ? 'font-semibold' : '' }}" role="menuitem">
                                <span class="mr-2">🇰🇭</span> ខ្មែរ
                            </a>
                            <a href="{{ route('language.switch', 'en') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'font-semibold' : '' }}" role="menuitem">
                                <span class="mr-2">🇺🇸</span> English
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-semibold text-gray-600">
                                            {{ substr(Auth::user()->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-sm">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 capitalize">{{ __(Auth::user()->role) }}</div>
                                    </div>
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ trans_db('profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ trans_db('log_out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @unless(auth()->user()->isCoordinator())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reports.dashboard')" :active="request()->routeIs('reports.dashboard')">
                        {{ __('Analytics Dashboard') }}
                    </x-responsive-nav-link>
                @endunless

                @if(in_array(auth()->user()->role, ['admin', 'teacher', 'mentor', 'viewer']))
                    <x-responsive-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                        {{ trans_db('assessments') }}
                    </x-responsive-nav-link>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                    <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        {{ trans_db('students') }}
                    </x-responsive-nav-link>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'mentor']))
                    <x-responsive-nav-link :href="route('mentoring.index')" :active="request()->routeIs('mentoring.*')">
                        {{ trans_db('mentoring') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ trans_db('reports') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('help.index')" :active="request()->routeIs('help.*')">
                    {{ trans_db('help') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-gray-500 capitalize mt-1">{{ __(Auth::user()->role) }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ trans_db('profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ trans_db('log_out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
<nav x-data="{ open: false }" class="bg-white">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        {{ __('TaRL Project') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                <a 
           href="https://plp.moeys.gov.kh" class="inline-flex 
           items-center px-1 pt-1 border-b-2 border-transparent 
           text-sm font-medium leading-5 text-gray-500 
           hover:text-gray-700 hover:border-gray-300 
           focus:outline-none focus:text-gray-700 
           focus:border-gray-300 transition duration-150 
           ease-in-out">
                                 {{ __('PLP') }}
                             </a>
                    @if(auth()->check() && !auth()->user()->isCoordinator())
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher', 'mentor', 'viewer']))
                    <x-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                        {{ __('Assessments') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher']))
                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        {{ __('Students') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'mentor']))
                    <x-nav-link :href="route('mentoring.index')" :active="request()->routeIs('mentoring.*')">
                        {{ __('Mentoring') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && !auth()->user()->isCoordinator())
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        {{ __('Reports') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'coordinator']))
                    <x-nav-link :href="route('coordinator.workspace')" :active="request()->routeIs('coordinator.*') || request()->routeIs('imports.*') || request()->routeIs('localization.*')">
                        {{ __('Coordinator Workspace') }}
                    </x-nav-link>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <x-nav-link :href="route('administration.index')" :active="request()->routeIs('administration.*')">
                        {{ __('Administration') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Language Switcher -->
                <x-language-switcher />
                
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                <div>{{ auth()->check() ? Auth::user()->name : 'Guest' }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->check() ? __(ucfirst(Auth::user()->role)) : 'Guest' }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Info Section -->
                        <div class="px-4 py-3">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->check() ? Auth::user()->name : 'Guest' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->check() ? Auth::user()->email : 'guest@example.com' }}</p>
                        </div>
                        
                        <div class="border-t border-gray-100"></div>
                        
                        <!-- Profile Links -->
                        <div class="px-2 py-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('My Profile') }}
                            </a>
                            
                            <a href="{{ route('profile.edit') }}#password-update" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                {{ __('Change Password') }}
                            </a>
                        </div>
                        
                        
                        <!-- Help & Support -->
                        <div class="border-t border-gray-100"></div>
                        <div class="px-2 py-2">
                            <a href="{{ route('help.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Help & Support') }}
                            </a>
                            
                            <a href="{{ route('about') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('About') }}
                            </a>
                        </div>

                        <!-- Sign Out -->
                        <div class="border-t border-gray-100"></div>
                        <div class="px-2 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Sign Out') }}
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
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
            @if(auth()->check() && !auth()->user()->isCoordinator())
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher', 'mentor', 'viewer']))
            <x-responsive-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                {{ __('Assessments') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'teacher'))
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                {{ __('Students') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'mentor'))
            <x-responsive-nav-link :href="route('mentoring.index')" :active="request()->routeIs('mentoring.*')">
                {{ __('Mentoring') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && !auth()->user()->isCoordinator())
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Reports') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'coordinator']))
            <x-responsive-nav-link :href="route('coordinator.workspace')" :active="request()->routeIs('coordinator.*') || request()->routeIs('imports.*') || request()->routeIs('localization.*')">
                {{ __('Coordinator Workspace') }}
            </x-responsive-nav-link>
            @endif
            
            @if(auth()->check() && auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('administration.index')" :active="request()->routeIs('administration.*')">
                {{ __('Administration') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ auth()->check() ? Auth::user()->name : 'Guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->check() ? Auth::user()->email : 'guest@example.com' }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ auth()->check() ? __(ucfirst(Auth::user()->role)) : 'Guest' }}</div>
            </div>

            <!-- Language Switcher in Mobile Menu -->
            <div class="px-4 mt-3">
                <div class="text-xs text-gray-400 mb-2">{{ __('Language') }}</div>
                <div class="flex space-x-2">
                    <a href="{{ url('/language/en') }}" class="flex-1 flex items-center justify-center px-3 py-2 border {{ app()->getLocale() == 'en' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300' }} rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-3 mr-2">
                        EN
                    </a>
                    <a href="{{ url('/language/km') }}" class="flex-1 flex items-center justify-center px-3 py-2 border {{ app()->getLocale() == 'km' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300' }} rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <img src="https://flagcdn.com/w20/kh.png" alt="Khmer" class="w-5 h-3 mr-2">
                        KM
                    </a>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('My Profile') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.edit') . '#password-update'">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    {{ __('Change Password') }}
                </x-responsive-nav-link>
                
                
                <div class="border-t border-gray-200 my-2"></div>
                <x-responsive-nav-link :href="route('help.index')">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Help & Support') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('about')">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('About') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <div class="border-t border-gray-200 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" 
                            class="text-red-600">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

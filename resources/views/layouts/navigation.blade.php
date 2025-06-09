<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <!-- Admin Navigation -->
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                                {{ __('Payments') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
                                {{ __('Admin Analytics') }}
                            </x-nav-link>
                        @else
                            <!-- User Navigation -->
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('quiz.index')" :active="request()->routeIs('quiz.*')">
                                {{ __('My Quizzes') }}
                            </x-nav-link>
                            <x-nav-link :href="route('manual-quiz.create')" :active="request()->routeIs('manual-quiz.*')">
                                {{ __('Create Quiz') }}
                            </x-nav-link>
                            <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                                {{ __('AI Generator') }}
                            </x-nav-link>
                            <x-nav-link :href="route('analytics.dashboard')" :active="request()->routeIs('analytics.*')">
                                {{ __('Analytics') }}
                            </x-nav-link>
                            @if(auth()->user()->isPremium())
                                <x-nav-link href="#" :active="false">
                                    {{ __('Flashcards') }}
                                </x-nav-link>
                            @endif
                            <x-nav-link :href="route('tier.compare')" :active="request()->routeIs('tier.*')">
                                {{ __('Plans') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Tier Badge -->
                @if(auth()->user()->isUser())
                <div class="mr-4 flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->tier_badge_color }}">
                        {{ ucfirst(auth()->user()->tier) }}
                    </span>
                    @if(auth()->user()->isFree())
                    <span class="text-xs text-gray-500">
                        {{ auth()->user()->question_attempts }} left
                    </span>
                    @endif
                </div>
                @endif
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if(auth()->user()->isUser())
                            <!-- Analytics for Users -->
                            <x-dropdown-link :href="route('analytics.dashboard')">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="text-purple-600">{{ __('My Analytics') }}</span>
                                </div>
                            </x-dropdown-link>

                            @if(auth()->user()->isFree())
                                <x-dropdown-link :href="route('tier.upgrade')">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                        <span class="text-green-600 font-medium">{{ __('Upgrade to Premium') }}</span>
                                    </div>
                                </x-dropdown-link>
                            @else
                                <!-- Premium Badge -->
                                <x-dropdown-link href="#" class="cursor-default">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                        <span class="text-yellow-600 font-medium">{{ __('Premium Member') }}</span>
                                    </div>
                                </x-dropdown-link>
                            @endif
                        @endif

                        @if(auth()->user()->isAdmin())
                            <!-- Admin Analytics -->
                            <x-dropdown-link :href="route('admin.analytics')">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-blue-600">{{ __('Platform Analytics') }}</span>
                                </div>
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <!-- Guest Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 mr-4">Login</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Register
                </a>
            </div>
            @endauth

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
                @if(auth()->user()->isAdmin())
                    <!-- Admin Responsive Navigation -->
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                        {{ __('Payments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
                        {{ __('Platform Analytics') }}
                    </x-responsive-nav-link>
                @else
                    <!-- User Responsive Navigation -->
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('quiz.index')" :active="request()->routeIs('quiz.*')">
                        {{ __('My Quizzes') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('manual-quiz.create')" :active="request()->routeIs('manual-quiz.*')">
                        {{ __('Create Quiz') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        {{ __('AI Generator') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('analytics.dashboard')" :active="request()->routeIs('analytics.*')">
                        {{ __('Analytics') }}
                    </x-responsive-nav-link>
                    @if(auth()->user()->isPremium())
                        <x-responsive-nav-link href="#" :active="false">
                            {{ __('Flashcards') }}
                        </x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('tier.compare')" :active="request()->routeIs('tier.*')">
                        {{ __('Plans') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->tier_badge_color }}">
                        {{ ucfirst(Auth::user()->tier) }}
                    </span>
                    @if(Auth::user()->isFree())
                        <span class="text-xs text-gray-500 ml-2">
                            {{ Auth::user()->question_attempts }} attempts left
                        </span>
                    @endif
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if(auth()->user()->isUser())
                    <!-- User Analytics Link -->
                    <x-responsive-nav-link :href="route('analytics.dashboard')">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-purple-600">{{ __('My Analytics') }}</span>
                        </div>
                    </x-responsive-nav-link>

                    @if(auth()->user()->isFree())
                        <x-responsive-nav-link :href="route('tier.upgrade')">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span class="text-green-600 font-medium">{{ __('Upgrade to Premium') }}</span>
                            </div>
                        </x-responsive-nav-link>
                    @else
                        <!-- Premium Member Badge -->
                        <div class="px-4 py-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span class="text-yellow-600 font-medium text-sm">{{ __('Premium Member') }}</span>
                            </div>
                        </div>
                    @endif
                @endif

                @if(auth()->user()->isAdmin())
                    <!-- Admin Analytics Link -->
                    <x-responsive-nav-link :href="route('admin.analytics')">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-blue-600">{{ __('Platform Analytics') }}</span>
                        </div>
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <!-- Guest Responsive Menu -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endauth
    </div>
</nav>
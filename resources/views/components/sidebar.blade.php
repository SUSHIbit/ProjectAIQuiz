@props(['user'])

<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-100">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 bg-slate-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <div x-show="sidebarOpen || window.innerWidth >= 1024"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 lg:relative lg:inset-0 lg:translate-x-0 lg:flex lg:flex-col">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Quiz Gen</h1>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- User Info -->
        <div class="px-6 py-4 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-slate-700">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 truncate">{{ $user->name }}</p>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->tier_badge_color }}">
                            {{ ucfirst($user->tier) }}
                            @if($user->isPremium())
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endif
                        </span>
                        @if($user->isFree())
                            <span class="text-xs text-slate-500">{{ $user->question_attempts }} left</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @if($user->isAdmin())
                <!-- Admin Navigation -->
                <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="home">
                    Admin Dashboard
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" icon="users">
                    Users
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')" icon="credit-card">
                    Payments
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')" icon="chart-bar">
                    Analytics
                </x-sidebar-link>
            @else
                <!-- User Navigation -->
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('welcome')" :active="request()->routeIs('welcome')" icon="upload">
                    Upload & Generate
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('quiz.index')" :active="request()->routeIs('quiz.*')" icon="document-text">
                    My Quizzes
                </x-sidebar-link>
                
                <x-sidebar-link :href="route('manual-quiz.create')" :active="request()->routeIs('manual-quiz.*')" icon="plus-circle">
                    Create Quiz
                </x-sidebar-link>
                
                @if($user->isPremium())
                    <x-sidebar-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')" icon="collection">
                        <div class="flex items-center justify-between w-full">
                            <span>Flashcards</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Premium
                            </span>
                        </div>
                    </x-sidebar-link>
                @else
                    <a href="{{ route('flashcards.upgrade') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-slate-400 rounded-md hover:text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <div class="flex items-center justify-between w-full">
                            <span>Flashcards</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600 group-hover:bg-slate-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Locked
                            </span>
                        </div>
                    </a>
                @endif
                
                @if($user->isPremium())
                    <x-sidebar-link :href="route('analytics.dashboard')" :active="request()->routeIs('analytics.*')" icon="chart-line">
                        <div class="flex items-center justify-between w-full">
                            <span>Analytics</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Premium
                            </span>
                        </div>
                    </x-sidebar-link>
                @else
                    <a href="{{ route('analytics.upgrade') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-slate-400 rounded-md hover:text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="flex items-center justify-between w-full">
                            <span>Analytics</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600 group-hover:bg-slate-300">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Locked
                            </span>
                        </div>
                    </a>
                @endif
                
                <x-sidebar-link :href="route('tier.compare')" :active="request()->routeIs('tier.*')" icon="star">
                    <div class="flex items-center justify-between w-full">
                        <span>Plans</span>
                        @if($user->isFree())
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Upgrade
                            </span>
                        @endif
                    </div>
                </x-sidebar-link>
            @endif

            <!-- Divider -->
            <div class="pt-4 mt-4 border-t border-slate-200">
                <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" icon="user-circle">
                    Profile
                </x-sidebar-link>
            </div>
        </nav>

        <!-- Logout Button -->
        <div class="px-4 py-4 border-t border-slate-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:text-slate-900 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Mobile header -->
        <div class="lg:hidden bg-white border-b border-slate-200 px-4 py-3">
            <button @click="sidebarOpen = true" class="p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto bg-slate-50">
            {{ $slot }}
        </main>
    </div>
</div>
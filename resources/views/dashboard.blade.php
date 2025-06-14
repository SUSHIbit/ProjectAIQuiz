<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Tier Status Alert for Free Users with Low Attempts -->
        @if($user->isFree() && $user->question_attempts <= 1)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                           {{ $user->question_attempts === 0 ? 'No AI generations remaining!' : 'Only 1 AI generation remaining!' }}
                       </h3>
                       <div class="mt-2 text-sm text-yellow-700">
                           <p>Upgrade to Premium for unlimited AI quiz generations, flashcards, and exclusive features.</p>
                       </div>
                       <div class="mt-4">
                           <div class="-mx-2 -my-1.5 flex">
                               <a href="{{ route('tier.upgrade') }}" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                   Upgrade Now
                               </a>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       @endif

       <!-- User Info Card -->
       <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
           <div class="p-6 text-slate-900">
               <div class="flex items-center justify-between">
                   <div>
                       <h3 class="text-lg font-semibold">Welcome, {{ $user->name }}!</h3>
                       <p class="text-slate-600 mt-1">
                           Account Type: 
                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->tier_badge_color }}">
                               {{ ucfirst($user->tier) }}
                               @if($user->isPremium())
                                   <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                       <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                   </svg>
                               @endif
                           </span>
                       </p>
                       @if($user->isFree())
                           <p class="text-sm text-slate-500 mt-2">
                               Remaining AI Generations: 
                               <span class="font-medium {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                   {{ $user->attempts_display }}
                               </span>
                           </p>
                       @else
                           <p class="text-sm text-green-600 mt-2 font-medium">
                               ✨ Enjoy unlimited AI generations and premium features!
                           </p>
                       @endif
                   </div>
                   @if($user->isFree())
                   <div>
                       <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                           </svg>
                           Upgrade to Premium
                       </a>
                   </div>
                   @endif
               </div>
           </div>
       </div>

       <!-- Feature Cards -->
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           <!-- Manual Quiz Creator -->
           <div class="bg-white overflow-hidden shadow-sm rounded-lg">
               <div class="p-6">
                   <div class="flex items-center">
                       <div class="flex-shrink-0">
                           <div class="bg-green-100 p-3 rounded-full">
                               <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                               </svg>
                           </div>
                       </div>
                       <div class="ml-4">
                           <h3 class="text-lg font-medium text-slate-900">Manual Quiz Creator</h3>
                           <p class="text-sm text-slate-500">Create custom quizzes manually</p>
                           <p class="text-xs text-green-600 mt-1">Available for all users</p>
                       </div>
                   </div>
                   <div class="mt-4">
                       <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                           Create Quiz
                           <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                           </svg>
                       </a>
                   </div>
               </div>
           </div>

           <!-- AI Quiz Generator -->
           <div class="bg-white overflow-hidden shadow-sm rounded-lg {{ $user->isFree() && $user->question_attempts <= 0 ? 'opacity-60' : '' }}">
               <div class="p-6">
                   <div class="flex items-center">
                       <div class="flex-shrink-0">
                           <div class="bg-blue-100 p-3 rounded-full">
                               <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                               </svg>
                           </div>
                       </div>
                       <div class="ml-4">
                           <h3 class="text-lg font-medium text-slate-900">AI Quiz Generator</h3>
                           <p class="text-sm text-slate-500">Generate quizzes from uploaded documents</p>
                           @if($user->isFree())
                               <p class="text-xs {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                                   {{ $user->question_attempts }} attempts remaining
                               </p>
                           @endif
                       </div>
                   </div>
                   <div class="mt-4">
                       @if($user->canGenerateQuestions())
                           <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                               Start Generating
                               <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                               </svg>
                           </a>
                       @else
                           <div class="flex flex-col space-y-2">
                               <span class="text-sm text-red-600 font-medium">No attempts remaining</span>
                               <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                                   Upgrade for Unlimited
                                   <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                   </svg>
                               </a>
                           </div>
                       @endif
                   </div>
               </div>
           </div>

           <!-- My Quizzes -->
           <div class="bg-white overflow-hidden shadow-sm rounded-lg">
               <div class="p-6">
                   <div class="flex items-center">
                       <div class="flex-shrink-0">
                           <div class="bg-purple-100 p-3 rounded-full">
                               <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                               </svg>
                           </div>
                       </div>
                       <div class="ml-4">
                           <h3 class="text-lg font-medium text-slate-900">My Quizzes</h3>
                           <p class="text-sm text-slate-500">View and manage your quizzes</p>
                       </div>
                   </div>
                   <div class="mt-4">
                       <a href="{{ route('quiz.index') }}" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-500">
                           View Quizzes
                           <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                           </svg>
                       </a>
                   </div>
               </div>
           </div>

           @if($user->isPremium())
           <!-- Flashcards (Premium) -->
           <div class="bg-white overflow-hidden shadow-sm rounded-lg border-2 border-amber-200">
               <div class="p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <div class="bg-amber-100 p-3 rounded-full">
                              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                              </svg>
                          </div>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-medium text-slate-900">Flashcards</h3>
                          <p class="text-sm text-slate-500">Create and study with flashcards</p>
                          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mt-1">
                              <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                              </svg>
                              Premium Feature
                          </span>
                      </div>
                  </div>
                  <div class="mt-4 flex space-x-2">
                      <a href="{{ route('flashcards.index') }}" class="inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-500">
                          Manage Flashcards
                          <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </a>
                      <span class="text-slate-300">|</span>
                      <a href="{{ route('flashcards.create') }}" class="inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-500">
                          Create New
                      </a>
                  </div>
              </div>
          </div>
          @else
          <!-- Flashcards (Locked for Free users) -->
          <div class="bg-slate-50 overflow-hidden shadow-sm rounded-lg border-2 border-slate-200 opacity-75">
              <div class="p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <div class="bg-slate-200 p-3 rounded-full">
                              <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                              </svg>
                          </div>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-medium text-slate-500">Flashcards</h3>
                          <p class="text-sm text-slate-400">Create and study with flashcards</p>
                          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600 mt-1">
                              <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                              </svg>
                              Premium Required
                          </span>
                      </div>
                  </div>
                  <div class="mt-4">
                      <a href="{{ route('flashcards.upgrade') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                          Upgrade to Unlock
                          <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                          </svg>
                      </a>
                  </div>
              </div>
          </div>
          @endif

          <!-- Analytics -->
          @if($user->isPremium())
          <div class="bg-white overflow-hidden shadow-sm rounded-lg">
              <div class="p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <div class="bg-indigo-100 p-3 rounded-full">
                              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                              </svg>
                          </div>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-medium text-slate-900">Analytics</h3>
                          <p class="text-sm text-slate-500">Track your quiz performance</p>
                          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                              <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                              </svg>
                              Premium Feature
                          </span>
                      </div>
                  </div>
                  <div class="mt-4">
                      <a href="{{ route('analytics.dashboard') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                          View Analytics
                          <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </a>
                  </div>
              </div>
          </div>
          @else
          <!-- Analytics (Locked for Free users) -->
          <div class="bg-slate-50 overflow-hidden shadow-sm rounded-lg border-2 border-slate-200 opacity-75">
              <div class="p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <div class="bg-slate-200 p-3 rounded-full">
                              <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                              </svg>
                          </div>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-medium text-slate-500">Analytics</h3>
                          <p class="text-sm text-slate-400">Track your quiz performance</p>
                          <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600 mt-1">
                              <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                              </svg>
                              Premium Required
                          </span>
                      </div>
                  </div>
                  <div class="mt-4">
                      <a href="{{ route('analytics.upgrade') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                          Upgrade to Unlock
                          <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                          </svg>
                      </a>
                  </div>
              </div>
          </div>
          @endif

          <!-- Tier Comparison -->
          <div class="bg-white overflow-hidden shadow-sm rounded-lg">
              <div class="p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <div class="bg-orange-100 p-3 rounded-full">
                              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                              </svg>
                          </div>
                      </div>
                      <div class="ml-4">
                          <h3 class="text-lg font-medium text-slate-900">Compare Plans</h3>
                          <p class="text-sm text-slate-500">See all features and pricing</p>
                      </div>
                  </div>
                  <div class="mt-4">
                      <a href="{{ route('tier.compare') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-500">
                          View Comparison
                          <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                          </svg>
                      </a>
                  </div>
              </div>
          </div>
      </div>

      <!-- Premium Features Showcase for Free Users -->
      @if($user->isFree())
      <div class="mt-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg shadow-sm">
          <div class="p-6 text-white">
              <div class="flex items-center justify-between">
                  <div>
                      <h3 class="text-xl font-bold">🚀 Unlock Premium Features</h3>
                      <p class="mt-2 text-green-100">
                          Get unlimited AI generations, flashcards, analytics, custom timers, and advanced features for just RM5 one-time!
                      </p>
                      <ul class="mt-4 space-y-1 text-sm text-green-100">
                         <li class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Unlimited AI quiz & flashcard generations
                         </li>
                         <li class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             AI-powered flashcards with study mode
                         </li>
                         <li class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Detailed analytics and progress tracking
                         </li>
                         <li class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Custom quiz timers (5-60 minutes)
                         </li>
                         <li class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Up to 30 questions per quiz
                         </li>
                     </ul>
                 </div>
                 <div class="ml-6">
                     <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-white border border-transparent rounded-md font-semibold text-sm text-green-600 uppercase tracking-widest hover:bg-slate-50 focus:bg-slate-50 active:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                         </svg>
                         Upgrade Now - RM5
                     </a>
                 </div>
             </div>
         </div>
     </div>
     @endif

     <!-- Quick Stats for Premium Users -->
     @if($user->isPremium())
     <div class="mt-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg shadow-sm">
         <div class="p-6 text-white">
             <div class="flex items-center justify-between">
                 <div>
                      <h3 class="text-xl font-bold flex items-center">
                         <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                         </svg>
                         Premium Member Benefits
                     </h3>
                     <p class="mt-2 text-yellow-100">
                         You have access to all premium features! Create unlimited AI content and track your progress.
                     </p>
                     <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-yellow-100">
                         <div class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Unlimited AI Generations
                         </div>
                         <div class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Flashcard Creation & Study
                         </div>
                         <div class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Advanced Analytics
                         </div>
                         <div class="flex items-center">
                             <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                             </svg>
                             Custom Quiz Timers
                         </div>
                     </div>
                 </div>
                 <div class="ml-6">
                     <div class="text-center">
                         <div class="text-3xl font-bold text-white">∞</div>
                         <div class="text-sm text-yellow-100">Unlimited Access</div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     @endif
 </div>
</x-app-layout>
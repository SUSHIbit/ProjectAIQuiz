<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>AI Quiz Generator</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">AI Quiz Generator</h1>
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <!-- Hero Section -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Create Quizzes & Flashcards Instantly</h2>
                            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-6">
                                Generate AI-powered quizzes and flashcards from your documents or create custom content manually. 
                                Perfect for students, teachers, and professionals.
                            </p>
                            
                            <!-- Quick Action Buttons -->
                            @auth
                            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                                <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Create Manual Quiz
                                </a>
                                
                                @if(auth()->user()->isPremium())
                                <a href="{{ route('flashcards.create') }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Create Flashcards
                                </a>
                                @endif
                                
                                <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    My Content
                                </a>
                            </div>
                            @endauth
                        </div>

                        <!-- File Upload Section -->
                        <div class="mb-12">
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Upload Document for AI Generation</h3>
                                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                                    Upload your PDF or DOCX file and let our AI generate comprehensive quizzes or flashcards instantly. 
                                   Perfect for studying or creating assessments from existing content.
                               </p>
                               @auth
                               @if(auth()->user()->isPremium())
                               <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                   <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                       <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                   </svg>
                                   Premium: Generate unlimited quizzes & flashcards
                               </div>
                               @else
                               <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                   Free: {{ auth()->user()->question_attempts }} AI generations remaining
                               </div>
                               @endif
                               @endauth
                           </div>

                           <!-- Upload Area -->
                           <div class="max-w-xl mx-auto">
                               <div id="upload-area" class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors cursor-pointer bg-white dark:bg-gray-800 dark:border-gray-600">
                                   <div id="upload-content">
                                       <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                           <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                       </svg>
                                       <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Drop your file here</h3>
                                       <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">or click to browse files</p>
                                       <p class="text-xs text-gray-400 dark:text-gray-500">Supports: PDF, DOC, DOCX (Max: 10MB)</p>
                                   </div>
                                   
                                   <!-- Success State -->
                                   <div id="upload-success" class="hidden">
                                       <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                       </svg>
                                       <h3 class="text-lg font-medium text-green-900 dark:text-green-100 mb-2">File uploaded successfully!</h3>
                                       <p id="file-info" class="text-sm text-gray-600 dark:text-gray-300 mb-4"></p>
                                       <button id="remove-file" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm underline">Remove file</button>
                                   </div>

                                   <!-- Loading State -->
                                   <div id="upload-loading" class="hidden">
                                       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                       <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Uploading...</h3>
                                   </div>
                               </div>
                               
                               <input type="file" id="file-input" class="hidden" accept=".pdf,.doc,.docx">
                               
                               <!-- Error Message -->
                               <div id="error-message" class="hidden mt-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
                                   <p class="text-red-800 dark:text-red-200 text-sm"></p>
                               </div>

                               <!-- Continue Button with Choice -->
                               <div id="continue-section" class="hidden mt-6 text-center">
                                   <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">What would you like to generate from your uploaded file?</p>
                                   <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                       <button id="generate-quiz-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                           </svg>
                                           Generate Quiz
                                       </button>
                                       
                                       @auth
                                       @if(auth()->user()->isPremium())
                                       <button id="generate-flashcards-btn" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                           </svg>
                                           Generate Flashcards
                                           <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                                               Premium
                                           </span>
                                       </button>
                                       @else
                                       <div class="relative group">
                                           <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-60">
                                               <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                               </svg>
                                               Generate Flashcards
                                               <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                                   Premium
                                               </span>
                                           </button>
                                           <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs text-white bg-gray-900 rounded-lg whitespace-nowrap">
                                               Upgrade to Premium to unlock flashcards
                                           </div>
                                       </div>
                                       @endif
                                       @endauth
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Features Grid -->
                       <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                           <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                               <div class="relative flex items-center gap-6 lg:items-end">
                                   <div class="flex items-start gap-6 lg:flex-col">
                                       <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                           <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                           </svg>
                                       </div>

                                       <div class="pt-3 sm:pt-5 lg:pt-0">
                                           <h2 class="text-xl font-semibold text-black dark:text-white">Manual Quiz Creation</h2>

                                           <p class="mt-4 text-sm/relaxed">
                                               Create custom quizzes manually with full control over questions, answers, and explanations. Available for all users with unlimited quiz creation.
                                           </p>
                                           
                                           @auth
                                           <div class="mt-4">
                                               <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center text-sm font-medium text-[#FF2D20] hover:text-[#FF2D20]/80">
                                                   Create Manual Quiz →
                                               </a>
                                           </div>
                                           @endauth
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                               <div class="relative flex items-center gap-6 lg:items-end">
                                   <div class="flex items-start gap-6 lg:flex-col">
                                       <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                           <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                               <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                           </svg>
                                       </div>

                                       <div class="pt-3 sm:pt-5 lg:pt-0">
                                           <h2 class="text-xl font-semibold text-black dark:text-white">AI-Powered Quiz Generation</h2>

                                           <p class="mt-4 text-sm/relaxed">
                                               Upload your PDF or DOCX files and let our AI generate comprehensive multiple-choice quizzes instantly. Perfect for studying from existing materials.
                                           </p>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           @auth
                           @if(auth()->user()->isPremium())
                           <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20] border-2 border-yellow-200">
                               <div class="relative flex items-center gap-6 lg:items-end">
                                   <div class="flex items-start gap-6 lg:flex-col">
                                       <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:size-16">
                                          <svg class="size-5 sm:size-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                          </svg>
                                      </div>

                                      <div class="pt-3 sm:pt-5 lg:pt-0">
                                          <h2 class="text-xl font-semibold text-black dark:text-white flex items-center">
                                              AI-Powered Flashcards
                                              <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                  </svg>
                                                  Premium
                                              </span>
                                          </h2>

                                          <p class="mt-4 text-sm/relaxed">
                                              Create interactive flashcards manually or generate them automatically from your documents. Study with our interactive flip-card mode and track your progress.
                                          </p>
                                          
                                          <div class="mt-4">
                                              <a href="{{ route('flashcards.index') }}" class="inline-flex items-center text-sm font-medium text-yellow-600 hover:text-yellow-500">
                                                  Manage Flashcards →
                                              </a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          @endif
                          @endauth

                          <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                              <div class="relative flex items-center gap-6 lg:items-end">
                                  <div class="flex items-start gap-6 lg:flex-col">
                                      <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                          <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                          </svg>
                                      </div>

                                      <div class="pt-3 sm:pt-5 lg:pt-0">
                                          <h2 class="text-xl font-semibold text-black dark:text-white">Analytics & Progress Tracking</h2>

                                          <p class="mt-4 text-sm/relaxed">
                                              Monitor your learning progress with detailed analytics. Track quiz performance, flashcard study sessions, identify weak areas, and see improvement over time.
                                          </p>
                                          
                                          @auth
                                          <div class="mt-4">
                                              <a href="{{ route('analytics.dashboard') }}" class="inline-flex items-center text-sm font-medium text-[#FF2D20] hover:text-[#FF2D20]/80">
                                                  View Analytics →
                                              </a>
                                          </div>
                                          @endauth
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                              <div class="relative flex items-center gap-6 lg:items-end">
                                  <div class="flex items-start gap-6 lg:flex-col">
                                      <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                          <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                          </svg>
                                      </div>

                                      <div class="pt-3 sm:pt-5 lg:pt-0">
                                          <h2 class="text-xl font-semibold text-black dark:text-white">Premium Features</h2>

                                          <p class="mt-4 text-sm/relaxed">
                                              Upgrade to Premium for unlimited AI generations, flashcard creation, custom timers, advanced analytics, and up to 30 questions per quiz. Only RM5 for lifetime access!
                                          </p>
                                          
                                          @auth
                                              @if(auth()->user()->isFree())
                                              <div class="mt-4">
                                                  <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center text-sm font-medium text-[#FF2D20] hover:text-[#FF2D20]/80">
                                                      Upgrade to Premium →
                                                  </a>
                                              </div>
                                              @endif
                                          @endauth
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="mt-16 flex justify-center">
                          @auth
                              <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                  Go to Dashboard
                              </a>
                          @else
                              <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                  Get Started for Free
                              </a>
                          @endauth
                      </div>
                  </main>

                  <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                      AI Quiz Generator &copy; {{ date('Y') }}. Built with Laravel.
                  </footer>
              </div>
          </div>
      </div>

      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const uploadArea = document.getElementById('upload-area');
              const fileInput = document.getElementById('file-input');
              const uploadContent = document.getElementById('upload-content');
              const uploadSuccess = document.getElementById('upload-success');
              const uploadLoading = document.getElementById('upload-loading');
              const errorMessage = document.getElementById('error-message');
              const continueSection = document.getElementById('continue-section');
              const fileInfo = document.getElementById('file-info');
              const removeFileBtn = document.getElementById('remove-file');
              const generateQuizBtn = document.getElementById('generate-quiz-btn');
              const generateFlashcardsBtn = document.getElementById('generate-flashcards-btn');

              if (!uploadArea) return;

              // Handle click on upload area
              uploadArea.addEventListener('click', function() {
                  fileInput.click();
              });

              // Handle drag and drop
              uploadArea.addEventListener('dragover', function(e) {
                  e.preventDefault();
                  uploadArea.classList.add('border-blue-400', 'bg-blue-50');
              });

              uploadArea.addEventListener('dragleave', function(e) {
                  e.preventDefault();
                  uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
              });

              uploadArea.addEventListener('drop', function(e) {
                  e.preventDefault();
                  uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                  
                  const files = e.dataTransfer.files;
                  if (files.length > 0) {
                      handleFileUpload(files[0]);
                  }
              });

              // Handle file input change
              fileInput.addEventListener('change', function(e) {
                  if (e.target.files.length > 0) {
                      handleFileUpload(e.target.files[0]);
                  }
              });

              // Handle file upload
              function handleFileUpload(file) {
                  // Hide error message
                  hideError();
                  
                  // Show loading state
                  showLoading();

                  const formData = new FormData();
                  formData.append('file', file);
                  formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                  fetch('{{ route("file.upload") }}', {
                      method: 'POST',
                      body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                      hideLoading();
                      
                      if (data.success) {
                          showSuccess(data.file_info);
                      } else {
                          showError(data.message);
                          
                          // If unauthorized, redirect to login
                          if (data.redirect) {
                              setTimeout(() => {
                                  window.location.href = data.redirect;
                              }, 2000);
                          }
                      }
                  })
                  .catch(error => {
                      hideLoading();
                      showError('An error occurred while uploading the file.');
                  });
              }

              // Remove file
              if (removeFileBtn) {
                  removeFileBtn.addEventListener('click', function() {
                      fetch('{{ route("file.remove") }}', {
                          method: 'DELETE',
                          headers: {
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                              'Content-Type': 'application/json'
                          }
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              resetUploadArea();
                          }
                      });
                  });
              }

              // Generate quiz
              if (generateQuizBtn) {
                  generateQuizBtn.addEventListener('click', function() {
                      window.location.href = '{{ route("quiz.generator") }}';
                  });
              }

              // Generate flashcards (Premium only)
              if (generateFlashcardsBtn) {
                  generateFlashcardsBtn.addEventListener('click', function() {
                      window.location.href = '{{ route("flashcards.ai.generator") }}';
                  });
              }

              // Helper functions
              function showLoading() {
                  uploadContent.classList.add('hidden');
                  uploadSuccess.classList.add('hidden');
                  uploadLoading.classList.remove('hidden');
              }

              function hideLoading() {
                  uploadLoading.classList.add('hidden');
              }

              function showSuccess(fileData) {
                  uploadContent.classList.add('hidden');
                  uploadSuccess.classList.remove('hidden');
                  continueSection.classList.remove('hidden');
                  
                  fileInfo.textContent = `${fileData.name} (${fileData.size})`;
              }

              function showError(message) {
                  uploadContent.classList.remove('hidden');
                  uploadSuccess.classList.add('hidden');
                  continueSection.classList.add('hidden');
                  
                  errorMessage.classList.remove('hidden');
                  errorMessage.querySelector('p').textContent = message;
              }

              function hideError() {
                  errorMessage.classList.add('hidden');
              }

              function resetUploadArea() {
                  uploadContent.classList.remove('hidden');
                  uploadSuccess.classList.add('hidden');
                  uploadLoading.classList.add('hidden');
                  continueSection.classList.add('hidden');
                  hideError();
                  fileInput.value = '';
              }
          });
      </script>
  </body>
</html>
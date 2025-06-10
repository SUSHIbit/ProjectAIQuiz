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
    <body class="antialiased bg-slate-50">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-slate-600 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <h1 class="text-4xl font-bold text-slate-900">AI Quiz Generator</h1>
                    </div>
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-slate-700 ring-1 ring-transparent transition hover:text-slate-900 focus:outline-none focus-visible:ring-slate-500">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-slate-700 ring-1 ring-transparent transition hover:text-slate-900 focus:outline-none focus-visible:ring-slate-500">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-slate-700 ring-1 ring-transparent transition hover:text-slate-900 focus:outline-none focus-visible:ring-slate-500">
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
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Create Quizzes & Flashcards Instantly</h2>
                        <p class="text-xl text-slate-600 max-w-3xl mx-auto mb-6">
                            Generate AI-powered quizzes and flashcards from your documents or create custom content manually. 
                            Perfect for students, teachers, and professionals.
                        </p>
                        
                        <!-- Quick Action Buttons -->
                        @auth
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                            <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Create Manual Quiz
                            </a>
                            
                            @if(auth()->user()->isPremium())
                            <a href="{{ route('flashcards.create') }}" class="inline-flex items-center px-6 py-3 bg-amber-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Create Flashcards
                            </a>
                            @endif
                            
                            <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-6 py-3 bg-violet-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-violet-700 focus:bg-violet-700 active:bg-violet-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">Upload Document for AI Generation</h3>
                            <p class="text-slate-600 max-w-2xl mx-auto">
                                Upload your PDF, Word document, or PowerPoint presentation and let our AI generate comprehensive quizzes or flashcards instantly. 
                               Perfect for studying or creating assessments from existing content.
                           </p>
                           @auth
                           @if(auth()->user()->isPremium())
                           <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
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

                       <!-- Enhanced Upload Area -->
                       <div class="max-w-xl mx-auto">
                           <div id="upload-area" class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-slate-400 transition-all duration-200 cursor-pointer bg-white shadow-sm hover:shadow-md">
                               <div id="upload-content">
                                   <div class="mb-4">
                                       <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                      </svg>
                                  </div>
                                  <h3 class="text-lg font-semibold text-slate-900 mb-2">Drop your file here</h3>
                                  <p class="text-sm text-slate-600 mb-4">or click to browse files</p>
                                  <div class="flex flex-wrap justify-center gap-2 text-xs text-slate-500">
                                      <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-50 text-red-700">
                                          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                              <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                          </svg>
                                          PDF
                                      </span>
                                      <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                              <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                          </svg>
                                          DOC/DOCX
                                      </span>
                                      <span class="inline-flex items-center px-2 py-1 rounded-full bg-orange-50 text-orange-700">
                                          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                              <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                          </svg>
                                          PPT/PPTX
                                      </span>
                                  </div>
                                  <p class="text-xs text-slate-400 mt-2">Maximum file size: 15MB</p>
                              </div>
                              
                              <!-- Success State -->
                              <div id="upload-success" class="hidden">
                                  <div class="mb-4">
                                      <svg class="mx-auto h-16 w-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                      </svg>
                                  </div>
                                  <h3 class="text-lg font-semibold text-emerald-900 mb-2">File uploaded successfully!</h3>
                                  <p id="file-info" class="text-sm text-slate-600 mb-4"></p>
                                  <button id="remove-file" class="text-red-600 hover:text-red-800 text-sm font-medium underline transition-colors">Remove file</button>
                              </div>

                              <!-- Loading State -->
                              <div id="upload-loading" class="hidden">
                                  <div class="mb-4">
                                      <div class="animate-spin rounded-full h-16 w-16 border-4 border-slate-200 border-t-slate-600 mx-auto"></div>
                                  </div>
                                  <h3 class="text-lg font-semibold text-slate-900 mb-2">Uploading...</h3>
                                  <p class="text-sm text-slate-600">Please wait while we process your file</p>
                              </div>

                              <!-- Upload Progress -->
                              <div id="upload-progress" class="hidden mt-4">
                                  <div class="w-full bg-slate-200 rounded-full h-2">
                                      <div id="progress-bar" class="bg-slate-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                  </div>
                                  <p class="text-xs text-slate-500 mt-2">Processing file...</p>
                              </div>
                          </div>
                          
                          <input type="file" id="file-input" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx">
                          
                          <!-- Error Message -->
                          <div id="error-message" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                              <div class="flex">
                                  <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                  </svg>
                                  <div>
                                      <h3 class="text-sm font-medium text-red-800">Upload Failed</h3>
                                      <p class="text-sm text-red-700 mt-1"></p>
                                  </div>
                              </div>
                          </div>

                          <!-- Continue Button with Choice -->
                          <div id="continue-section" class="hidden mt-6 text-center">
                              <p class="text-sm text-slate-600 mb-4">What would you like to generate from your uploaded file?</p>
                              <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                  <button id="generate-quiz-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wide hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                      </svg>
                                      Generate Quiz
                                  </button>
                                  
                                  @auth
                                  @if(auth()->user()->isPremium())
                                  <button id="generate-flashcards-btn" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wide hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                      </svg>
                                      Generate Flashcards
                                      <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-amber-200 text-amber-800">
                                          Premium
                                      </span>
                                  </button>
                                  @else
                                  <div class="relative group">
                                      <button disabled class="inline-flex items-center px-4 py-2 bg-slate-400 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wide cursor-not-allowed opacity-60">
                                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                          </svg>
                                          Generate Flashcards
                                          <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                                              Premium
                                          </span>
                                      </button>
                                      <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs text-white bg-slate-900 rounded-lg whitespace-nowrap z-10">
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
                      <div class="flex flex-col items-start gap-6 overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:shadow-md lg:pb-10">
                          <div class="relative flex items-center gap-6 lg:items-end">
                              <div class="flex items-start gap-6 lg:flex-col">
                                  <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 sm:size-16">
                                      <svg class="size-5 sm:size-6 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                      </svg>
                                  </div>

                                  <div class="pt-3 sm:pt-5 lg:pt-0">
                                      <h2 class="text-xl font-semibold text-slate-900">Manual Quiz Creation</h2>

                                      <p class="mt-4 text-sm/relaxed text-slate-600">
                                          Create custom quizzes manually with full control over questions, answers, and explanations. Available for all users with unlimited quiz creation.
                                      </p>
                                      
                                      @auth
                                      <div class="mt-4">
                                          <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                              Create Manual Quiz →
                                          </a>
                                      </div>
                                      @endauth
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="flex flex-col items-start gap-6 overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:shadow-md lg:pb-10">
                          <div class="relative flex items-center gap-6 lg:items-end">
                              <div class="flex items-start gap-6 lg:flex-col">
                                  <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 sm:size-16">
                                      <svg class="size-5 sm:size-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                      </svg>
                                  </div>

                                  <div class="pt-3 sm:pt-5 lg:pt-0">
                                      <h2 class="text-xl font-semibold text-slate-900">AI-Powered Quiz Generation</h2>

                                      <p class="mt-4 text-sm/relaxed text-slate-600">
                                          Upload your PDF, Word documents, or PowerPoint presentations and let our AI generate comprehensive multiple-choice quizzes instantly. Perfect for studying from existing materials.
                                      </p>
                                  </div>
                              </div>
                          </div>
                      </div>

                      @auth
                      @if(auth()->user()->isPremium())
                      <div class="flex flex-col items-start gap-6 overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-2 ring-amber-200 transition duration-300 hover:shadow-md lg:pb-10">
                          <div class="relative flex items-center gap-6 lg:items-end">
                              <div class="flex items-start gap-6 lg:flex-col">
                                  <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 sm:size-16">
                                     <svg class="size-5 sm:size-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                     </svg>
                                 </div>

                                 <div class="pt-3 sm:pt-5 lg:pt-0">
                                     <h2 class="text-xl font-semibold text-slate-900 flex items-center">
                                         AI-Powered Flashcards
                                         <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                             <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                 <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                             </svg>
                                             Premium
                                         </span>
                                     </h2>

                                     <p class="mt-4 text-sm/relaxed text-slate-600">
                                         Create interactive flashcards manually or generate them automatically from your documents. Study with our interactive flip-card mode and track your progress.
                                     </p>
                                     
                                     <div class="mt-4">
                                         <a href="{{ route('flashcards.index') }}" class="inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                                             Manage Flashcards →
                                         </a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     @endif
                     @endauth

                     <div class="flex flex-col items-start gap-6 overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:shadow-md lg:pb-10">
                         <div class="relative flex items-center gap-6 lg:items-end">
                             <div class="flex items-start gap-6 lg:flex-col">
                                 <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 sm:size-16">
                                     <svg class="size-5 sm:size-6 text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                     </svg>
                                 </div>

                                 <div class="pt-3 sm:pt-5 lg:pt-0">
                                     <h2 class="text-xl font-semibold text-slate-900">Analytics & Progress Tracking</h2>

                                     <p class="mt-4 text-sm/relaxed text-slate-600">
                                         Monitor your learning progress with detailed analytics. Track quiz performance, flashcard study sessions, identify weak areas, and see improvement over time.
                                     </p>
                                     
                                     @auth
                                     <div class="mt-4">
                                         <a href="{{ route('analytics.dashboard') }}" class="inline-flex items-center text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors">
                                             View Analytics →
                                         </a>
                                     </div>
                                     @endauth
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="flex flex-col items-start gap-6 overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:shadow-md lg:pb-10">
                         <div class="relative flex items-center gap-6 lg:items-end">
                             <div class="flex items-start gap-6 lg:flex-col">
                                 <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 sm:size-16">
                                     <svg class="size-5 sm:size-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                     </svg>
                                 </div>

                                 <div class="pt-3 sm:pt-5 lg:pt-0">
                                     <h2 class="text-xl font-semibold text-slate-900">Premium Features</h2>

                                     <p class="mt-4 text-sm/relaxed text-slate-600">
                                         Upgrade to Premium for unlimited AI generations, flashcard creation, custom timers, advanced analytics, and up to 30 questions per quiz. Only RM5 for lifetime access!
                                     </p>
                                     
                                     @auth
                                         @if(auth()->user()->isFree())
                                         <div class="mt-4">
                                             <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
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
                         <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                             Go to Dashboard
                         </a>
                     @else
                         <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                             Get Started for Free
                         </a>
                     @endauth
                 </div>
             </main>

             <footer class="py-16 text-center text-sm text-slate-600">
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
         const uploadProgress = document.getElementById('upload-progress');
         const progressBar = document.getElementById('progress-bar');
         const errorMessage = document.getElementById('error-message');
         const continueSection = document.getElementById('continue-section');
         const fileInfo = document.getElementById('file-info');
         const removeFileBtn = document.getElementById('remove-file');
         const generateQuizBtn = document.getElementById('generate-quiz-btn');
         const generateFlashcardsBtn = document.getElementById('generate-flashcards-btn');

         let uploadInProgress = false;
         let uploadId = null;

         if (!uploadArea) return;

         // Handle click on upload area
         uploadArea.addEventListener('click', function() {
             if (!uploadInProgress) {
                 fileInput.click();
             }
         });

         // Handle drag and drop with enhanced visual feedback
         uploadArea.addEventListener('dragover', function(e) {
             e.preventDefault();
             if (!uploadInProgress) {
                 uploadArea.classList.add('border-slate-400', 'bg-slate-50', 'scale-105');
                 uploadArea.style.transform = 'scale(1.02)';
             }
         });

         uploadArea.addEventListener('dragleave', function(e) {
             e.preventDefault();
             uploadArea.classList.remove('border-slate-400', 'bg-slate-50', 'scale-105');
             uploadArea.style.transform = 'scale(1)';
         });

         uploadArea.addEventListener('drop', function(e) {
             e.preventDefault();
             uploadArea.classList.remove('border-slate-400', 'bg-slate-50', 'scale-105');
             uploadArea.style.transform = 'scale(1)';
             
             if (!uploadInProgress) {
                 const files = e.dataTransfer.files;
                 if (files.length > 0) {
                     handleFileUpload(files[0]);
                 }
             }
         });

         // Handle file input change
         fileInput.addEventListener('change', function(e) {
             if (e.target.files.length > 0 && !uploadInProgress) {
                 handleFileUpload(e.target.files[0]);
             }
         });

         // Handle file upload with progress simulation
         function handleFileUpload(file) {
             if (uploadInProgress) {
                 showError('Upload already in progress. Please wait...');
                 return;
             }

             uploadInProgress = true;
             uploadId = Math.random().toString(36).substring(2, 15);
             
             // Hide error message
             hideError();
             
             // Show loading state with progress
             showLoading();

             const formData = new FormData();
             formData.append('file', file);
             formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
             formData.append('upload_id', uploadId);

             // Simulate progress for better UX
             let progress = 0;
             const progressInterval = setInterval(() => {
                 progress += Math.random() * 30;
                 if (progress > 90) progress = 90;
                 updateProgress(progress);
             }, 300);

             fetch('{{ route("file.upload") }}', {
                 method: 'POST',
                 body: formData
             })
             .then(response => response.json())
             .then(data => {
                 clearInterval(progressInterval);
                 updateProgress(100);
                 
                 setTimeout(() => {
                     hideLoading();
                     uploadInProgress = false;
                     
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
                 }, 500);
             })
             .catch(error => {
                 clearInterval(progressInterval);
                 hideLoading();
                 uploadInProgress = false;
                 showError('An error occurred while uploading the file. Please try again.');
                 console.error('Upload error:', error);
             });
         }

         // Remove file with confirmation
         if (removeFileBtn) {
             removeFileBtn.addEventListener('click', function() {
                 if (confirm('Are you sure you want to remove this file?')) {
                     removeFile();
                 }
             });
         }

         function removeFile() {
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
             })
             .catch(error => {
                 console.error('Remove file error:', error);
                 showError('Failed to remove file. Please try again.');
             });
         }

         // Generate quiz
         if (generateQuizBtn) {
             generateQuizBtn.addEventListener('click', function() {
                 generateQuizBtn.disabled = true;
                 generateQuizBtn.innerHTML = `
                     <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                         <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                         <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                     </svg>
                     Loading...
                 `;
                 window.location.href = '{{ route("quiz.generator") }}';
             });
         }

         // Generate flashcards (Premium only)
         if (generateFlashcardsBtn) {
             generateFlashcardsBtn.addEventListener('click', function() {
                 generateFlashcardsBtn.disabled = true;
                 generateFlashcardsBtn.innerHTML = `
                     <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                         <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                         <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                     </svg>
                     Loading...
                 `;
                 window.location.href = '{{ route("flashcards.ai.generator") }}';
             });
         }

         // Helper functions
         function showLoading() {
             uploadContent.classList.add('hidden');
             uploadSuccess.classList.add('hidden');
             uploadLoading.classList.remove('hidden');
             uploadProgress.classList.remove('hidden');
             updateProgress(0);
         }

         function hideLoading() {
             uploadLoading.classList.add('hidden');
             uploadProgress.classList.add('hidden');
         }

         function updateProgress(percent) {
             if (progressBar) {
                 progressBar.style.width = percent + '%';
             }
         }

         function showSuccess(fileData) {
             uploadContent.classList.add('hidden');
             uploadSuccess.classList.remove('hidden');
             continueSection.classList.remove('hidden');
             
             fileInfo.innerHTML = `
                 <span class="font-medium">${fileData.name}</span>
                 <span class="text-slate-500">(${fileData.size})</span>
                 ${fileData.type ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 ml-2">${fileData.type}</span>` : ''}
             `;
         }

         function showError(message) {
             uploadContent.classList.remove('hidden');
             uploadSuccess.classList.add('hidden');
             continueSection.classList.add('hidden');
             
             errorMessage.classList.remove('hidden');
             errorMessage.querySelector('p').textContent = message;
             
             // Auto-hide error after 10 seconds
             setTimeout(() => {
                 hideError();
             }, 10000);
         }

         function hideError() {
             errorMessage.classList.add('hidden');
         }

         function resetUploadArea() {
             uploadContent.classList.remove('hidden');
             uploadSuccess.classList.add('hidden');
             uploadLoading.classList.add('hidden');
             uploadProgress.classList.add('hidden');
             continueSection.classList.add('hidden');
             hideError();
             fileInput.value = '';
             uploadInProgress = false;
             uploadId = null;
         }

         // File input validation
         fileInput.addEventListener('change', function(e) {
             const file = e.target.files[0];
             if (file) {
                 // Check file size (15MB limit)
                 if (file.size > 15 * 1024 * 1024) {
                     showError('File size must be less than 15MB. Please choose a smaller file.');
                     fileInput.value = '';
                     return;
                 }

                 // Check file type
                 const allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];
                 const fileExtension = file.name.split('.').pop().toLowerCase();
                 
                 if (!allowedTypes.includes(fileExtension)) {
                     showError('Only PDF, DOC/DOCX, and PPT/PPTX files are allowed.');
                     fileInput.value = '';
                     return;
                 }
             }
         });

         // Prevent multiple file drops
         document.addEventListener('dragover', function(e) {
             e.preventDefault();
         });

         document.addEventListener('drop', function(e) {
             e.preventDefault();
         });

         // Add visual feedback for file drag
         document.addEventListener('dragenter', function(e) {
             if (e.target === uploadArea || uploadArea.contains(e.target)) {
                 return;
             }
             e.preventDefault();
         });
     });
 </script>
</body>
</html>
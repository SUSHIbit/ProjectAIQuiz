<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Upgrade to Premium') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Error/Success Messages -->
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Payment Error</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Current Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Current Account Status</h3>
                            <p class="text-slate-600 mt-1">
                                You are currently on the 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->tier_badge_color }}">
                                    {{ $user->tier_display }}
                                </span>
                                plan
                            </p>
                            @if($user->isFree())
                                @if($subscriptionStatus === 'expired')
                                    <p class="text-sm text-red-600 mt-2 font-medium">
                                        ⚠️ Your subscription has expired. Renew now to restore premium features.
                                    </p>
                                @else
                                    <p class="text-sm text-slate-500 mt-2">
                                        Remaining AI Generations: 
                                        <span class="font-medium {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                            {{ $user->question_attempts }}
                                        </span>
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Selection Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Choose Your Plan</h1>
                <p class="text-xl text-slate-600">Select the subscription that works best for you</p>
                
                @if($subscriptionStatus === 'expired')
                    <div class="mt-4 inline-flex items-center px-4 py-2 bg-amber-100 border border-amber-200 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="text-amber-800 font-medium">Renew your subscription to restore premium access</span>
                  </div>
              @endif
          </div>

          <!-- Plan Comparison Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <!-- Monthly Plan -->
              <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                  <div class="p-8">
                      <div class="text-center">
                          <h3 class="text-2xl font-bold text-slate-900 mb-2">Monthly Plan</h3>
                          <div class="mb-4">
                              <span class="text-4xl font-bold text-blue-600">RM15</span>
                              <span class="text-slate-500 ml-1">/month</span>
                          </div>
                          <p class="text-slate-600 mb-6">Perfect for trying out premium features</p>
                      </div>
                      
                      <ul class="space-y-4 mb-8">
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Unlimited AI quiz generations</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">AI-powered flashcards</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Custom timers (5-60 minutes)</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Advanced analytics</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Up to 30 questions per quiz</span>
                          </li>
                      </ul>

                      <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                          @csrf
                          <input type="hidden" name="plan_type" value="monthly">
                          <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                              </svg>
                              Subscribe Monthly - RM15
                          </button>
                      </form>
                  </div>
              </div>

              <!-- Yearly Plan -->
              <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-green-500 hover:shadow-xl transition-shadow duration-300 relative">
                  <!-- Popular Badge -->
                  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                      <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                          </svg>
                          Best Value
                      </span>
                  </div>

                  <div class="p-8 pt-12">
                      <div class="text-center">
                          <h3 class="text-2xl font-bold text-slate-900 mb-2">Yearly Plan</h3>
                          <div class="mb-2">
                              <span class="text-4xl font-bold text-green-600">RM120</span>
                              <span class="text-slate-500 ml-1">/year</span>
                          </div>
                          <div class="mb-4">
                              <span class="text-lg text-green-600 font-medium">Save RM60!</span>
                              <span class="text-sm text-slate-500 block">(RM10/month when paid yearly)</span>
                          </div>
                          <p class="text-slate-600 mb-6">Best value for committed learners</p>
                      </div>
                      
                      <ul class="space-y-4 mb-8">
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900 font-medium">Everything in Monthly</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900 font-medium">33% savings (RM60 off)</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">No monthly billing hassle</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Priority customer support</span>
                          </li>
                          <li class="flex items-center">
                              <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="text-slate-900">Early access to new features</span>
                          </li>
                      </ul>

                      <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                          @csrf
                          <input type="hidden" name="plan_type" value="yearly">
                          <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                              </svg>
                              Subscribe Yearly - RM120
                          </button>
                      </form>
                  </div>
              </div>
          </div>

          <!-- Free Plan Comparison -->
          <div class="bg-slate-50 rounded-2xl p-6 mb-8">
              <div class="text-center">
                  <h3 class="text-xl font-semibold text-slate-900 mb-4">Free Plan Features</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                      <div class="flex items-center justify-center">
                          <svg class="h-5 w-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                          </svg>
                          <span class="text-slate-600">5 AI generations (updated)</span>
                      </div>
                      <div class="flex items-center justify-center">
                          <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                          </svg>
                          <span class="text-slate-600">Unlimited manual quizzes</span>
                      </div>
                      <div class="flex items-center justify-center">
                          <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                          </svg>
                          <span class="text-slate-600">PDF export</span>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Security & Trust -->
          <div class="bg-blue-50 rounded-lg p-6 mb-8">
              <div class="flex items-center justify-center space-x-8">
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="text-blue-900 font-medium">Secure Payment</span>
                  </div>
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="text-blue-900 font-medium">Instant Activation</span>
                  </div>
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="text-blue-900 font-medium">Cancel Anytime</span>
                  </div>
              </div>
              <p class="text-center text-blue-800 text-sm mt-3">
                  @if(config('services.toyyibpay.sandbox'))
                      🧪 Sandbox Mode - Use test card: 4111111111111111
                  @else
                      Powered by ToyyibPay - Malaysia's trusted payment gateway
                  @endif
              </p>
          </div>

          <!-- Back to Dashboard -->
          <div class="text-center">
              <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  Back to Dashboard
              </a>
          </div>

          <!-- Testing Information (only show in sandbox) -->
          @if(config('services.toyyibpay.sandbox'))
          <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
              <div class="flex">
                  <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                      </svg>
                  </div>
                  <div class="ml-3">
                      <h3 class="text-sm font-medium text-yellow-800">
                          Testing Mode Information
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700">
                          <p class="mb-2">This is a sandbox environment for testing payments. Use these test details:</p>
                          <ul class="list-disc list-inside space-y-1">
                              <li><strong>Test Card:</strong> 4111111111111111</li>
                              <li><strong>CVV:</strong> Any 3 digits (e.g., 123)</li>
                              <li><strong>Expiry:</strong> Any future date (e.g., 12/26)</li>
                              <li><strong>Name:</strong> Any name</li>
                          </ul>
                          <p class="mt-2 text-xs">No real money will be charged in sandbox mode.</p>
                      </div>
                  </div>
              </div>
          </div>
          @endif
      </div>
  </div>
</x-app-layout>
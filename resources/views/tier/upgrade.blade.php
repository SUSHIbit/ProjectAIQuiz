<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upgrade to Premium') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                        <h3 class="text-sm font-medium text-red-800">
                            Payment Error
                        </h3>
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
                        <h3 class="text-sm font-medium text-green-800">
                            Success
                        </h3>
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
                        <h3 class="text-sm font-medium text-blue-800">
                            Information
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Current Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Current Account Status</h3>
                            <p class="text-gray-600 mt-1">
                                You are currently on the 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->tier_badge_color }}">
                                    {{ ucfirst($user->tier) }}
                                </span>
                                plan
                            </p>
                            @if($user->isFree())
                            <p class="text-sm text-gray-500 mt-2">
                                Remaining AI Generations: 
                                <span class="font-medium {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    {{ $user->question_attempts }}
                                </span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier Comparison -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Free Tier -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900">Free</h3>
                            <p class="text-4xl font-bold text-gray-600 mt-4">RM0</p>
                            <p class="text-gray-500 mt-2">Forever</p>
                        </div>
                        
                        <ul class="mt-6 space-y-4">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">3 AI quiz generations</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">Manual quiz creation</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">10 questions per quiz</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-600">PDF export</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-400">No timer features</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-400">No flashcards</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Premium Tier -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-500">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-gray-900">Premium</h3>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Recommended
                                </span>
                            </div>
                            <p class="text-4xl font-bold text-green-600 mt-4">RM5</p>
                            <p class="text-gray-500 mt-2">One-time payment</p>
                            
                            @if(config('services.toyyibpay.sandbox'))
                            <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Sandbox Mode - Test Payment
                            </div>
                            @endif
                        </div>
                        
                        <ul class="mt-6 space-y-4">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">Unlimited AI generations</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900">Manual quiz creation</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">10, 20, or 30 questions</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900">PDF export</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">Custom timers (5-60 mins)</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">AI-generated flashcards</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">Advanced analytics</span>
                            </li>
                        </ul>

                        <div class="mt-6">
                            @if($user->isPremium())
                            <div class="w-full text-center py-3 bg-green-100 text-green-800 rounded-md font-semibold">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               You Already Have Premium!
                           </div>
                           @else
                           <form method="POST" action="{{ route('payment.initiate') }}" id="payment-form">
                               @csrf
                               <button type="submit" id="payment-button" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                   </svg>
                                   <span id="payment-button-text">
                                       @if(config('services.toyyibpay.sandbox'))
                                           Test Payment - RM 5.00
                                       @else
                                           Proceed to Payment - RM 5.00
                                       @endif
                                   </span>
                                   <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="payment-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                       <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                       <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                   </svg>
                               </button>
                           </form>
                           @endif
                           
                           <div class="mt-4 flex items-center justify-center space-x-4">
                               <div class="flex items-center">
                                   <svg class="w-5 h-5 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   <span class="text-sm text-gray-600">Secure Payment</span>
                               </div>
                               <div class="flex items-center">
                                   <svg class="w-5 h-5 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   <span class="text-sm text-gray-600">Instant Activation</span>
                               </div>
                               <div class="flex items-center">
                                   <svg class="w-5 h-5 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   <span class="text-sm text-gray-600">One-time Payment</span>
                               </div>
                           </div>
                           
                           <p class="text-xs text-gray-500 text-center mt-2">
                               @if(config('services.toyyibpay.sandbox'))
                                   🧪 Sandbox Mode - Use test card: 4111111111111111
                               @else
                                   Powered by ToyyibPay - Malaysia's trusted payment gateway
                               @endif
                           </p>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Debug Section (only show in sandbox) -->
           @if(config('services.toyyibpay.sandbox'))
           <div class="mt-6">
               <button id="debug-config" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                   </svg>
                   Debug Configuration
               </button>
               
               <div id="debug-results" class="hidden mt-4 bg-gray-50 rounded-lg p-4">
                   <h4 class="font-medium text-gray-900 mb-2">Debug Information:</h4>
                   <pre id="debug-output" class="text-xs bg-white p-3 rounded border overflow-auto max-h-60"></pre>
               </div>
           </div>
           @endif

           <!-- Back to Dashboard -->
           <div class="mt-6 text-center">
               <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   Back to Dashboard
               </a>
           </div>

           <!-- Testing Information (only show in sandbox) -->
           @if(config('services.toyyibpay.sandbox'))
           <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
               <div class="flex">
                   <div class="flex-shrink-0">
                       <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                       </svg>
                   </div>
                   <div class="ml-3">
                       <h3 class="text-sm font-medium text-blue-800">
                           Testing Mode Information
                       </h3>
                       <div class="mt-2 text-sm text-blue-700">
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

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const paymentForm = document.getElementById('payment-form');
           const paymentButton = document.getElementById('payment-button');
           const paymentButtonText = document.getElementById('payment-button-text');
           const paymentSpinner = document.getElementById('payment-spinner');
           const debugButton = document.getElementById('debug-config');
           const debugResults = document.getElementById('debug-results');
           const debugOutput = document.getElementById('debug-output');

           // Handle payment form submission
           if (paymentForm) {
               paymentForm.addEventListener('submit', function(e) {
                   // Show loading state
                   paymentButton.disabled = true;
                   paymentButtonText.textContent = 'Processing...';
                   paymentSpinner.classList.remove('hidden');
                   
                   // Note: Don't prevent default - let the form submit normally
                   // The server will handle the redirect to ToyyibPay
               });
           }

           // Handle debug button
           if (debugButton) {
               debugButton.addEventListener('click', function() {
                   fetch('{{ route("payment.debug") }}')
                       .then(response => response.json())
                       .then(data => {
                           debugOutput.textContent = JSON.stringify(data, null, 2);
                           debugResults.classList.remove('hidden');
                       })
                       .catch(error => {
                           debugOutput.textContent = 'Error: ' + error.message;
                           debugResults.classList.remove('hidden');
                       });
               });
           }
       });
   </script>
</x-app-layout>
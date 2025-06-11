<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Renew Your Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Expired Subscription Alert -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">
                            Your Subscription Has Expired
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Your premium subscription expired on {{ $latestSubscription->subscription_expires_at->format('M d, Y') }}. Renew now to restore access to all premium features including unlimited AI generations, flashcards, and advanced analytics.</p>
                        </div>
                        @if($latestSubscription)
                        <div class="mt-4 text-sm text-red-600">
                            <p><strong>Previous Plan:</strong> {{ $latestSubscription->plan_display_name }}</p>
                            <p><strong>Last Payment:</strong> {{ $latestSubscription->formatted_amount }} on {{ $latestSubscription->paid_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Renewal Options -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Renew Your Subscription</h1>
                <p class="text-xl text-gray-600">Choose your renewal plan to continue enjoying premium features</p>
            </div>

            <!-- Quick Renewal (Same Plan) -->
            @if($latestSubscription)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Quick Renewal</h3>
                        <p class="text-blue-700 mt-1">Renew with your previous plan: {{ $latestSubscription->plan_display_name }}</p>
                        <p class="text-sm text-blue-600 mt-2">
                            <strong>Price:</strong> {{ $latestSubscription->formatted_amount }}
                            @if($latestSubscription->isYearlyPlan())
                                <span class="ml-2 text-green-600 font-medium">(Save RM60 vs Monthly)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('tier.process-upgrade') }}">
                            @csrf
                            <input type="hidden" name="plan_type" value="{{ $latestSubscription->plan_type }}">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Quick Renew
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Or Choose Different Plan -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Or Choose a Different Plan</h2>
            </div>

            <!-- Plan Selection Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Monthly Plan -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-200">
                    <div class="p-6">
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Monthly Plan</h3>
                            <div class="mb-4">
                                <span class="text-3xl font-bold text-blue-600">RM15</span>
                                <span class="text-gray-500 ml-1">/month</span>
                            </div>
                        </div>
                        
                        <ul class="space-y-3 mb-6 text-sm">
                            <li class="flex items-center">
                                <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">Unlimited AI generations</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">AI-powered flashcards</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">Advanced analytics</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">Up to 30 questions per quiz</span>
                           </li>
                       </ul>

                       <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                           @csrf
                           <input type="hidden" name="plan_type" value="monthly">
                           <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Renew Monthly - RM15
                           </button>
                       </form>
                   </div>
               </div>

               <!-- Yearly Plan -->
               <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-green-500 relative">
                   <!-- Recommended Badge -->
                   <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                       <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                           Recommended
                       </span>
                   </div>

                   <div class="p-6 pt-8">
                       <div class="text-center">
                           <h3 class="text-xl font-bold text-gray-900 mb-2">Yearly Plan</h3>
                           <div class="mb-2">
                               <span class="text-3xl font-bold text-green-600">RM120</span>
                               <span class="text-gray-500 ml-1">/year</span>
                           </div>
                           <div class="mb-4">
                               <span class="text-green-600 font-medium">Save RM60!</span>
                               <span class="text-sm text-gray-500 block">(RM10/month)</span>
                           </div>
                       </div>
                       
                       <ul class="space-y-3 mb-6 text-sm">
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900 font-medium">Everything in Monthly</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900 font-medium">33% savings</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">Priority support</span>
                           </li>
                           <li class="flex items-center">
                               <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                               </svg>
                               <span class="text-gray-900">Early access to features</span>
                           </li>
                       </ul>

                       <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                           @csrf
                           <input type="hidden" name="plan_type" value="yearly">
                           <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Renew Yearly - RM120
                           </button>
                       </form>
                   </div>
               </div>
           </div>

           <!-- Continue with Free -->
           <div class="mt-8 text-center">
               <p class="text-gray-600 mb-4">Not ready to renew? You can continue using the free plan with limited features.</p>
               <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   Continue with Free Plan
               </a>
           </div>
       </div>
   </div>
</x-app-layout>
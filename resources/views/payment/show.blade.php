<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Payment Status -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status_badge_color }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Payment Reference</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->payment_ref }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Amount</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $payment->formatted_amount }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($payment->paid_at)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Paid At</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->paid_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($payment->isPending() && $payment->payment_url)
                    <!-- Payment Action -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="bg-blue-50 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Complete Your Payment
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Click the button below to proceed to secure payment with ToyyibPay.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ $payment->payment_url }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Pay Now - {{ $payment->formatted_amount }}
                            </a>
                            
                            <button onclick="checkPaymentStatus()" class="inline-flex items-center justify-center px-4 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Check Status
                            </button>
                        </div>

                        <div class="mt-4 text-center">
                            <form method="POST" action="{{ route('payment.cancel', $payment->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-500 text-sm underline">
                                    Cancel Payment
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    @if($payment->isSuccess())
                    <!-- Success Message -->
                    <div class="border-t border-gray-200 pt-6">
                       <div class="bg-green-50 rounded-lg p-4 mb-4">
                           <div class="flex">
                               <div class="flex-shrink-0">
                                   <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                   </svg>
                               </div>
                               <div class="ml-3">
                                   <h3 class="text-sm font-medium text-green-800">
                                       Payment Successful!
                                   </h3>
                                   <div class="mt-2 text-sm text-green-700">
                                       <p>Your account has been upgraded to Premium. You now have unlimited access to all features!</p>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <div class="text-center">
                           <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Go to Dashboard
                           </a>
                       </div>
                   </div>
                   @endif

                   @if($payment->isFailed())
                   <!-- Failed Message -->
                   <div class="border-t border-gray-200 pt-6">
                       <div class="bg-red-50 rounded-lg p-4 mb-4">
                           <div class="flex">
                               <div class="flex-shrink-0">
                                   <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                   </svg>
                               </div>
                               <div class="ml-3">
                                   <h3 class="text-sm font-medium text-red-800">
                                       Payment Failed
                                   </h3>
                                   <div class="mt-2 text-sm text-red-700">
                                       <p>Your payment was not successful. You can try again or contact support if you continue to experience issues.</p>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <div class="flex flex-col sm:flex-row gap-4 justify-center">
                           <a href="{{ route('payment.initiate') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Try Again
                           </a>
                           <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Back to Upgrade
                           </a>
                       </div>
                   </div>
                   @endif

                   <!-- Back to Dashboard -->
                   <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                       <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-500 text-sm">
                           ← Back to Dashboard
                       </a>
                   </div>
               </div>
           </div>
       </div>
   </div>

   @if($payment->isPending())
   <script>
       function checkPaymentStatus() {
           fetch('{{ route("payment.status", $payment->id) }}')
               .then(response => response.json())
               .then(data => {
                   if (data.is_success) {
                       window.location.href = '{{ route("payment.success", $payment->id) }}';
                   } else if (data.is_failed) {
                       window.location.href = '{{ route("payment.failed", $payment->id) }}';
                   } else {
                       alert('Payment is still pending. Please complete your payment or wait a moment.');
                   }
               })
               .catch(error => {
                   console.error('Error checking payment status:', error);
                   alert('Error checking payment status. Please try again.');
               });
       }

       // Auto-check status every 30 seconds
       setInterval(checkPaymentStatus, 30000);
   </script>
   @endif
</x-app-layout>
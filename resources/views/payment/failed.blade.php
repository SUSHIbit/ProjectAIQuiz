<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Failed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <!-- Failed Icon -->
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                        <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>

                    <!-- Failed Message -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Failed</h3>
                    <p class="text-gray-600 mb-6">
                        We're sorry, but your payment could not be processed. This might be due to insufficient funds, card issues, or other payment problems.
                    </p>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-500">Payment Reference:</span>
                                <br>
                                <span class="text-gray-900">{{ $payment->payment_ref }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Amount:</span>
                                <br>
                                <span class="text-lg font-semibold text-gray-900">{{ $payment->formatted_amount }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Attempted:</span>
                                <br>
                                <span class="text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Status:</span>
                                <br>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Failed
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
                        <h4 class="font-medium text-blue-900 mb-2">Common Solutions:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Check that your card has sufficient funds</li>
                            <li>• Ensure your card details are correct</li>
                            <li>• Try using a different payment method</li>
                            <li>• Contact your bank if the issue persists</li>
                            <li>• Clear your browser cache and try again</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('payment.initiate') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Try Payment Again
                        </a>
                        
                        <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Upgrade
                        </a>

                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Continue with Free
                        </a>
                    </div>

                    <!-- Support -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Need help? Contact support with reference: {{ $payment->payment_ref }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
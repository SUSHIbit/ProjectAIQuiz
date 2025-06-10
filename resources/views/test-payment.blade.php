<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Payment Integration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">🧪 ToyyibPay Integration Testing</h3>
                    
                    <!-- Configuration Status -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Configuration Status:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">API Key:</span>
                                <span class="ml-2 {{ config('services.toyyibpay.api_key') ? 'text-green-600' : 'text-red-600' }}">
                                    {{ config('services.toyyibpay.api_key') ? '✅ Configured' : '❌ Missing' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium">Category Code:</span>
                                <span class="ml-2 {{ config('services.toyyibpay.category_code') ? 'text-green-600' : 'text-red-600' }}">
                                    {{ config('services.toyyibpay.category_code') ? '✅ Configured' : '❌ Missing' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium">Base URL:</span>
                                <span class="ml-2 text-blue-600">{{ config('services.toyyibpay.base_url') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Sandbox Mode:</span>
                                <span class="ml-2 {{ config('services.toyyibpay.sandbox') ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ config('services.toyyibpay.sandbox') ? '✅ Enabled' : '⚠️ Production' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Test Actions -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('test.payment.config') }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check Configuration
                            </a>
                            
                            <a href="{{ route('payment.initiate') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Test Payment Flow
                            </a>
                            
                            <a href="{{ route('payment.history') }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Payment History
                            </a>
                        </div>
                    </div>

                    <!-- Test Card Information -->
                    @if(config('services.toyyibpay.sandbox'))
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 mb-2">🧪 Test Card Details for Sandbox</h4>
                        <div class="text-sm text-yellow-700 space-y-1">
                            <p><strong>Card Number:</strong> 4111111111111111 (Success) | 4000000000000002 (Failed)</p>
                            <p><strong>CVV:</strong> Any 3 digits (e.g., 123)</p>
                            <p><strong>Expiry Date:</strong> Any future date (e.g., 12/26)</p>
                            <p><strong>Cardholder Name:</strong> Any name</p>
                        </div>
                    </div>
                    @endif

                    <!-- Callback URLs -->
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Callback URLs:</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Callback URL:</strong> <code class="bg-gray-200 px-1 rounded">{{ route('payment.callback') }}</code></p>
                            <p><strong>Return URL:</strong> <code class="bg-gray-200 px-1 rounded">{{ route('payment.return') }}</code></p>
                        </div>
                    </div>

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
</x-app-layout>
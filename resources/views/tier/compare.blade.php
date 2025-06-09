<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Compare Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                <p class="text-xl text-gray-600">Select the plan that best fits your needs</p>
            </div>

            <!-- Comparison Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Features
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Free
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Premium
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    AI Quiz Generation
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                   3 attempts
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                                   Unlimited
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Manual Quiz Creation
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Questions per Quiz
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                   10 questions
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                                   10, 20, or 30
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Timer Features
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                                   5-60 minutes
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Flashcards
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   PDF Export
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                           </tr>
                           <tr>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Advanced Analytics
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center">
                                   <svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                               </td>
                           </tr>
                           <tr class="bg-gray-50">
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                   Price
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-xl font-bold text-gray-900">
                                   Free
                               </td>
                               <td class="px-6 py-4 whitespace-nowrap text-center text-xl font-bold text-green-600">
                                   RM5 <span class="text-sm text-gray-500 font-normal">one-time</span>
                               </td>
                           </tr>
                       </tbody>
                   </table>
               </div>
           </div>

           <!-- Action Buttons -->
           <div class="mt-8 flex justify-center space-x-4">
               @auth
                   @if(auth()->user()->isFree())
                       <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                           Upgrade to Premium
                       </a>
                   @else
                       <div class="inline-flex items-center px-6 py-3 bg-green-100 border border-green-200 rounded-md">
                           <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                           </svg>
                           <span class="text-green-800 font-semibold">You have Premium!</span>
                       </div>
                   @endif
               @else
                   <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                       Get Started Free
                   </a>
               @endauth
               
               <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   Back to Dashboard
               </a>
           </div>
       </div>
   </div>
</x-app-layout>
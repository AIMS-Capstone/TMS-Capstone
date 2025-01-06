<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm font-medium">Go Back</span>
            </button>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('form0619E.update', ['id' => $form->id]) }}">
                @csrf
                @method('PUT')

                <div class="px-8 py-10">
                    <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-12">
                        Edit 0619E Form
                    </h1>

                    <!-- Filing Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- SOLID SI KRAZY old function ni laravel --}}
                        <div>
                            <label for="for_month" class="block text-sm font-medium text-gray-600">
                                For Month
                            </label>
                            <input type="month" name="for_month" id="for_month" 
                                value="{{ old('for_month', $form->for_month) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-600">
                                Due Date
                            </label>
                            <input type="date" name="due_date" id="due_date" 
                                value="{{ old('due_date', $form->due_date) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label for="amended_return" class="block text-sm font-medium text-gray-600">
                                Amended Return
                            </label>
                            <select name="amended_return" id="amended_return" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1" {{ $form->amended_return == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $form->amended_return == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div>
                            <label for="any_taxes_withheld" class="block text-sm font-medium text-gray-600">
                                Any Taxes Withheld?
                            </label>
                            <select name="any_taxes_withheld" id="any_taxes_withheld" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1" {{ $form->any_taxes_withheld == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $form->any_taxes_withheld == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div>
                            <label for="atc_id" class="block text-sm font-medium text-gray-600">
                                Alphanumeric Tax Code (ATC)
                            </label>
                            <select name="atc_id" id="atc_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled>Select ATC</option>
                                @foreach($atcs as $atc)
                                    <option value="{{ $atc->id }}" {{ old('atc_id', $form->atc_id) == $atc->id ? 'selected' : '' }}>
                                        {{ $atc->tax_code }} - {{ $atc->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6 px-8 flex flex-col justify-between">
                            <label class="block text-sm font-medium text-gray-700">Category of Withholding Agent</label>
                            <div class="mt-2 space-x-4">
                                <label>
                                    <input type="radio" name="category" value="1" 
                                        {{ $form->organization->category == 1 ? 'checked' : '' }} required>
                                    Private
                                </label>
                                <label>
                                    <input type="radio" name="category" value="0" 
                                        {{ $form->organization->category == 0 ? 'checked' : '' }} required>
                                    Government
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="tax_code" class="block text-sm font-medium text-gray-600">
                                Tax Code
                            </label>
                            <input type="text" name="tax_code" id="tax_code" 
                                value="{{ old('tax_code', $form->tax_code) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label for="amount_of_remittance" class="block text-sm font-medium text-gray-600">
                                Amount of Remittance
                            </label>
                            <input type="number" step="0.01" name="amount_of_remittance" 
                                value="{{ old('amount_of_remittance', $form->amount_of_remittance) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label for="remitted_previous" class="block text-sm font-medium text-gray-600">
                                Less: Amount Remitted Previously
                            </label>
                            <input type="number" step="0.01" name="remitted_previous" 
                                value="{{ old('remitted_previous', $form->remitted_previous) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="surcharge" class="block text-sm font-medium text-gray-600">
                                Surcharge
                            </label>
                            <input type="number" step="0.01" name="surcharge" 
                                value="{{ old('surcharge', $form->surcharge) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="interest" class="block text-sm font-medium text-gray-600">
                                Interest
                            </label>
                            <input type="number" step="0.01" name="interest" 
                                value="{{ old('interest', $form->interest) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="compromise" class="block text-sm font-medium text-gray-600">
                                Compromise
                            </label>
                            <input type="number" step="0.01" name="compromise" 
                                value="{{ old('compromise', $form->compromise) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mt-12 flex justify-center">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700">
                            Update Form
                        </button>
                    </div>  
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

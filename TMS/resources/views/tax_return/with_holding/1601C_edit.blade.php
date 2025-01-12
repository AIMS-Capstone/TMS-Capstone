<x-app-layout>
    <div class="py-6 flex justify-center">
        <div class="max-w-6xl w-full bg-white shadow-md rounded-lg">
            <div class="p-8">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-blue-900 text-center mb-6">Edit: BIR Form 1601C</h1>

                <form action="{{ route('form1601C.update', ['id' => $form->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Filing Period -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">General Information</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="number_of_sheets" class="block text-sm font-medium text-gray-700">
                                    Number of Sheets Attached <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="number_of_sheets" name="number_of_sheets"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->number_of_sheets }}" min="0" required>
                            </div>
                            <!-- Amended Return -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700">
                                    Amended Return? <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2 flex space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="amended_return" value="1" 
                                            {{ $form->amended_return == 1 ? 'checked' : '' }} required>
                                        <span class="ml-2">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="amended_return" value="0" 
                                            {{ $form->amended_return == 0 ? 'checked' : '' }} required>
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Any Taxes Withheld -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700">
                                    Any Taxes Withheld? <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2 flex space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="any_taxes_withheld" value="1" 
                                            {{ $form->any_taxes_withheld == 1 ? 'checked' : '' }} required>
                                        <span class="ml-2">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="any_taxes_withheld" value="0" 
                                            {{ $form->any_taxes_withheld == 0 ? 'checked' : '' }} required>
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Agent Category -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">Agent Information</h2>
                        <div class="mt-4">
                            <label for="agent_category" class="block text-sm font-medium text-gray-700">
                                Category of Withholding Agent <span class="text-red-500">*</span>
                            </label>
                            <select id="agent_category" name="agent_category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                required>
                                <option value="Private" {{ $form->agent_category === 'Private' ? 'selected' : '' }}>Private</option>
                                <option value="Government" {{ $form->agent_category === 'Government' ? 'selected' : '' }}>Government</option>
                            </select>
                        </div>
                    </div>

                    <!-- ATC Details -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">ATC Details</h2>
                        <div class="mt-4">
                            <label for="atc_id" class="block text-sm font-medium text-gray-700">
                                Alphanumeric Tax Code (ATC) <span class="text-red-500">*</span>
                            </label>
                            <select id="atc_id" name="atc_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                required>
                                <option value="" disabled>Select Tax Code</option>
                                @foreach ($atcs as $atc)
                                    <option value="{{ $atc->id }}" {{ $form->atc_id == $atc->id ? 'selected' : '' }}>
                                        {{ $atc->tax_code }} - {{ $atc->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tax Relief -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">Tax Relief</h2>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Are there payees availing of tax relief under Special Law or International Tax Treaty?
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 flex space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="tax_relief" value="1" {{ $form->tax_relief ? 'checked' : '' }} required>
                                    <span class="ml-2">Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tax_relief" value="0" {{ !$form->tax_relief ? 'checked' : '' }} required>
                                    <span class="ml-2">No</span>
                                </label>
                            </div>
                        </div>
                        @if ($form->tax_relief)
                            <div class="mt-4">
                                <label for="tax_relief_details" class="block text-sm font-medium text-gray-700">
                                    Specify Tax Relief
                                </label>
                                <input type="text" id="tax_relief_details" name="tax_relief_details"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->tax_relief_details }}">
                            </div>
                        @endif
                    </div>

                    <!-- Computation -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">Computation of Taxes</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="adjustment_taxes_withheld" class="block text-sm font-medium text-gray-700">
                                    Adjustment of Taxes Withheld
                                </label>
                                <input type="number" id="adjustment_taxes_withheld" name="adjustment_taxes_withheld"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->adjustment_taxes_withheld }}" step="0.01">
                            </div>
                            <div>
                                <label for="tax_remitted_return" class="block text-sm font-medium text-gray-700">
                                    Tax Remitted in Return Previously Filed
                                </label>
                                <input type="number" id="tax_remitted_return" name="tax_remitted_return"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->tax_remitted_return }}" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Penalties -->
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-blue-900">Penalties</h2>
                        <div class="mt-4 grid grid-cols-3 gap-6">
                            <div>
                                <label for="surcharge" class="block text-sm font-medium text-gray-700">Surcharge</label>
                                <input type="number" id="surcharge" name="surcharge"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->surcharge }}" step="0.01">
                            </div>
                            <div>
                                <label for="interest" class="block text-sm font-medium text-gray-700">Interest</label>
                                <input type="number" id="interest" name="interest"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->interest }}" step="0.01">
                            </div>
                            <div>
                                <label for="compromise" class="block text-sm font-medium text-gray-700">Compromise</label>
                                <input type="number" id="compromise" name="compromise"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-900 focus:ring focus:ring-blue-200"
                                    value="{{ $form->compromise }}" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="bg-blue-900 text-white py-2 px-6 rounded-md shadow-lg hover:bg-blue-950">
                            Update Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

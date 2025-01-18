<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('form1604E.preview', ['id' => $form1604E->withholding_id]) }}'" class="text-zinc-600 hover:text-zinc-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                    <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                </svg>
                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
            </button>
        </div>

        <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
            <div class="container px-4">
                <div class="flex justify-between items-center mt-2">
                    <div class="flex flex-col items-start">
                        <!-- BIR Form text on top -->
                        <p class="text-sm taxuri-color">BIR Form No. 1604-E Edit</p>
                        <p class="font-bold text-2xl taxuri-color">Annual Information Return <span class="text-sm">(of Creditable Income Taxes Withheld (Expanded))</span></p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 mb-4">
                    <div class="flex items-center">
                        <p class="taxuri-text font-normal text-sm">
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed, to generate the BIR form. 
                        </p>
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg mt-6 overflow-hidden">
            @if(session('success'))
                <div class="bg-green-500 text-white px-4 py-2 rounded-md mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-500 text-white px-4 py-2 rounded-md mb-6">
                    {{ session('warning') }}
                </div>
            @endif

            <form action="{{ route('form1604E.update', ['id' => $form1604E->withholding_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-8 py-10">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="amended_return" class="indent-4 block text-zinc-700 text-sm w-1/3">Amended Return?</label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="1" {{ optional($form1604E)->amended_return == 1 ? 'checked' : '' }}>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="0" {{ optional($form1604E)->amended_return == 0 ? 'checked' : '' }}>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>
                
                    <div class="mb-2 flex flex-row justify-between gap-12">
                        <label for="agent_category" class="indent-4 block text-zinc-700 text-sm w-full">Category of Withholding Agent</label>
                        <select id="agent_category" name="agent_category" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="Private" {{ $form1604E->agent_category == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Government" {{ $form1604E->agent_category == 'Government' ? 'selected' : '' }}>Government</option>
                        </select>
                    </div>

                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Update Report
                        </button>
                        <a href="{{ route('form1604E.preview', ['id' => $form1604E->withholding_id]) }}"
                            class="ml-4 text-zinc-600 hover:text-zinc-900 hover:font-bold font-medium py-2 px-4">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

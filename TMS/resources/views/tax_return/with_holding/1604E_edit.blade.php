<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-8 py-10">
                <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-8">
                    Edit Form 1604E
                </h1>

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

                    <div class="mb-6">
                        <label for="amended_return" class="block text-sm font-medium text-gray-700">Amended Return?</label>
                        <div class="mt-1">
                            <label>
                                <input type="radio" name="amended_return" value="1" 
                                    {{ optional($form1604E)->amended_return == 1 ? 'checked' : '' }}>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="ml-6">
                                <input type="radio" name="amended_return" value="0" 
                                    {{ optional($form1604E)->amended_return == 0 ? 'checked' : '' }}>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="agent_category" class="block text-sm font-medium text-gray-700">Category of Withholding Agent</label>
                        <select id="agent_category" name="agent_category" 
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                            <option value="Private" {{ $form1604E->agent_category == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Government" {{ $form1604E->agent_category == 'Government' ? 'selected' : '' }}>Government</option>
                        </select>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save Changes
                        </button>
                        <a href="{{ route('form1604E.preview', ['id' => $form1604E->withholding_id]) }}"
                           class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

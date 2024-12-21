<div class="flex justify-between items-center px-1 py-2 border-b border-b-gray-200 pb-4">
    <div class="px-4 sm:px-0 ">
        <h3 class="text-sm font-medium text-gray-900">{{ $redirection }}</h3>
        <p class="mt-1 text-2xl font-bold auth-color mb-2 ">
            {{ $description }}
        </p>
    </div>
    @if (!empty($wildcard))
        <div class="ml-auto">
            <p class="mt-1 text-2xl font-bold auth-color mb-2">
                {{ $wildcard }}
            </p>
        </div>
    @endif

    <!-- Notes and Activities Button -->

    @if (Route::currentRouteName() == 'transactions.show')
        <div class="ml-auto float-end justify-end items-center">
            <button type="button" class="group text-zinc-700 border border-gray-300 hover:bg-blue-900 hover:text-white font-medium rounded-lg text-sm px-2 py-2 flex items-center focus:outline-none focus:ring-1 focus:ring-gray-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition mr-2 group-hover:fill-white" viewBox="0 0 24 24" fill="none">
                    <path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/>
                    <path class="group-hover:fill-white" fill="#3f3f46" d="m14.295 4.98l4.724 4.725a2 2 0 0 1 .443 2.157l-2.365 5.913a2 2 0 0 1-1.605 1.24l-5.079.635q-.196.023-.41.056l-.444.072l-.232.042l-.723.14l-.495.105l-.745.168l-.955.228l-1.552.396l-.646.174a1.01 1.01 0 0 1-1.265-1.134l.034-.146l.295-1.112l.264-1.048l.228-.955l.167-.745l.105-.496l.141-.722l.08-.457l.064-.428l.66-5.28a2 2 0 0 1 1.241-1.605l5.913-2.365a2 2 0 0 1 2.157.443Zm-3.71 5.605a2 2 0 0 0-.507 1.968a1 1 0 0 0-.2.154L5.82 16.765a.2.2 0 0 0-.053.098l-.089.385l-.178.743l-.086.351a.2.2 0 0 0 .244.244l.717-.175l.763-.178a.2.2 0 0 0 .097-.054l4.058-4.058a1 1 0 0 0 .154-.199a2 2 0 1 0-.861-3.337Zm4.658-7.484a1 1 0 0 1 1.32-.084l.094.084L20.9 7.343a1 1 0 0 1-1.32 1.498l-.095-.084l-4.242-4.242a1 1 0 0 1 0-1.414"/>
                </svg>
                Notes and Activities
            </button>
        </div>
    @endif

    <div class="px-4 sm:px-0 ">
        {{ $aside ?? '' }}
    </div>
</div>

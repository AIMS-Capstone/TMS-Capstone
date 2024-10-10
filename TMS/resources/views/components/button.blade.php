<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center w-full justify-center py-2 bg-blue-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-950 active:bg-blue-900 focus:outline-none focus:ring-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center w-full justify-center py-2 bg-gradient-to-br from-blue-950 to-blue-900 hover:bg-gradient-to-bl border border-transparent rounded-xl font-bold text-sm text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

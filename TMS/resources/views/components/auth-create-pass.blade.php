{{-- For reset password --}}
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-sky-50">
    <div class="w-2/5 min-h-[560px] mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-2xl">
        <div class="flex items-center sm:justify-center">
            {{ $logo }}
        </div>
        {{ $slot }}
    </div>
</div>
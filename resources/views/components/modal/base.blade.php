<div class="fixed hidden z-10 inset-0 overflow-y-auto transition-opacity" {{ $attributes->merge(['id' => '']) }}>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white">
                <x-modal.header>{{ $title }}</x-modal.header>

                {{ $slot }}
            </div>
        </div>
    </div>
</div>

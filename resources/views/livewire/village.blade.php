<div>
    <x-modal.header>Desa</x-modal.header>
    <x-modal.body>
        <div class="flex flex-col">
            @forelse ($villages as $village)
                <div class="flex w-full">
                    <p class="grow align-middle mb-0">{{ $village->name }}</p>
                    <x-button.white class="px-2 py-1"
                        wire:click="$emit('openModal', 'village-modal', {{ json_encode(['id' => $village->id]) }})">Edit</x-button.white>
                    <x-button.error class="px-2 py-1 ml-2"
                        wire:click="$emit('openModal', 'village-delete-modal', {{ json_encode(['id' => $village->id]) }})">Hapus</x-button.error>
                </div>
            @empty
                <p class="text-xl text-center">Belum ada data desa</p>
            @endforelse
        </div>
    </x-modal.body>
</div>

<div class="grid grid-cols-4 gap-4 pt-4">
    <div class="col-span-4 lg:col-span-1">
        <x-card.base>
            <p class="text-xl font-bold">Kategori</p>
                <div class="flex flex-col pl-4 mt-4">
                    @forelse ($categories as $category)
                        <div class="flex mt-4">
                            @if ($category->id==$selectedCategoryId)
                                <x-button.black class="grow" wire:click="changeCategory({{ $category->id }})">
                                    {{ $category->name }}
                                </x-button.black>
                            @else
                                <x-button.white class="grow" wire:click="changeCategory({{ $category->id }})">
                                    {{ $category->name }}
                                </x-button.white>
                            @endif

                            <div x-data="{ open: false }" class="relative ml-2">
                                <x-button.white @click="open = true">
                                    <i class="fas fa-ellipsis-h"></i>
                                </x-button.white>

                                <ul x-show="open" @click.away="open = false" class="bg-white border border-gray-300 rounded absolute z-10 p-2">
                                    <li><x-button.white class="w-full px-2 py-1"
                                        wire:click="$emit('openModal', 'category-modal', {{ json_encode(['id' => $category->id]) }})">Edit</x-button.white></li>
                                    <li class="mt-2"><x-button.error class="w-full px-2 py-1"
                                        wire:click="$emit('openModal', 'category-delete-modal', {{ json_encode(['id' => $category->id]) }})">Hapus</x-button.error></li>
                                </ul>
                            </div>
                        </div>
                    @empty
                        <p class="font-bold text-center mb-0">Belum ada kategori.</p>
                    @endforelse
                </div>

            <x-button.success class="mt-10" wire:click="$emit('openModal', 'category-modal', {{ json_encode(['id' => '']) }})">+ Kategori</x-button.success>
        </x-card.base>
    </div>

    <div class="col-span-3">
        @if (!empty($selectedCategoryId))
            @livewire('subcategory', ['categoryId' => $selectedCategoryId])
        @endif
    </div>

    <div class="fixed bottom-12 right-12">
        <x-button.black wire:click="$emit('openModal', 'village')">Daftar Desa</x-button.black>
        <x-button.success wire:click="$emit('openModal', 'village-modal', {{ json_encode(['id' => '']) }})">+ Desa</x-button.success>
    </div>
</div>

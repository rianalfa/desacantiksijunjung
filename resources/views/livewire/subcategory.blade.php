<x-card.full>
    <div class="flex justify-between">
        <p class="text-xl font-bold">Kategori {{ $category->name }}</p>
        <div class="flex">
            <div class="flex items-center">
                <x-input.label for="villageId" value="Desa" />
                <x-input.select name="villageId" wire:model.defer="villageId" wire:change="changeVillage" class="ml-2">
                    @forelse ($villages as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @empty
                        <option selected hidden></option>
                    @endforelse
                </x-input.select>
            </div>
            <div class="flex items-center ml-4">
                <x-input.label for="year" value="Tahun" />
                <x-input.select name="year" wire:model.defer="year" wire:change="$emit('reloadSubcategory', {{ $category->id }})" class="ml-2">
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                </x-input.select>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
        @forelse ($subcategories as $subcategory)
            <div x-data="{ open: false }" class="flex justify-center relative">
                <x-button.white class="w-64 py-6" @click="open = true">
                    <p class="font-bold">{{ $subcategory->name }}</p>
                    <img src="{{ asset('storage/images/subcategory/'.$subcategory->subcategory_id.'.png') }}" alt="."
                        class="w-32 h-32 object-contain mx-auto">
                    @php
                        $banker = $subcategory->bankers()
                                            ->where('village_id', $village->id)
                                            ->where('year', $year)
                                            ->first();
                        echo !empty($banker) ? $banker->value : '-'
                    @endphp
                </x-button.white>

                @auth
                    <ul x-show="open" @click.away="open = false" class="bg-white border border-gray-300 rounded absolute top-8 z-10 p-2">
                        <li><x-button.white class="w-full px-2 py-1"
                            wire:click="$emit('openModal', 'subcategory-modal', {{ json_encode(['id' => $subcategory->subcategory_id, 'categoryId' => $category->id]) }})">Edit</x-button.white></li>
                        <li class="mt-2"><x-button.white class="w-full px-2 py-1"
                            wire:click="$emit('openModal', 'banker-modal', {{ json_encode([
                                'id' => !empty($banker) ? $banker->id : '',
                                'villageId' => $village->id,
                                'subcategoryId' => $subcategory->id,
                                'year' => $year,
                            ]) }})">Data</x-button.white></li>
                        <li class="mt-2"><x-button.error class="w-full px-2 py-1"
                            wire:click="$emit('openModal', 'subcategory-delete-modal', {{ json_encode(['id' => $subcategory->subcategory_id]) }})">Hapus</x-button.error></li>
                    </ul>
                @endauth
            </div>
        @empty
            <p class="font-bold text-center mb-0">Belum ada subkategori.</p>
        @endforelse
    </div>

    @auth
        <x-button.success class="mt-10" wire:click="$emit('openModal', 'subcategory-modal', {{ json_encode(['id' => '', 'categoryId' => $category->id]) }})">+ Subategori</x-button.success>
    @endauth
</x-card.full>

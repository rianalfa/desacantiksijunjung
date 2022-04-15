<x-card.full>
    <p class="text-xl text-center font-bold">Kategori {{ $category->name }}</p>
    <div class="flex flex-col lg:flex-row lg:justify-between">
        <div class="inline-flex">
            @if ($table==false)
                <x-button.white wire:click="changeTable(false)"
                    class="rounded-r-none">{{__('Card')}}</x-button.white>
                <x-button.black wire:click="changeTable(true)"
                    class="rounded-l-none">{{__('Table')}}</x-button.black>
            @else
                <x-button.black wire:click="changeTable(false)"
                    class="rounded-r-none">{{__('Card')}}</x-button.black>
                <x-button.white wire:click="changeTable(true)"
                    class="rounded-l-none">{{__('Table')}}</x-button.white>
            @endif
        </div>
        <div class="flex flex-col lg:flex-row">
            @if ($table==false)
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
            @else
                @auth
                    <x-button.success wire:click="$emit('openModal', 'bankers-excel-modal', {{ json_encode(['year' => $year]) }})">
                        Data Excel
                    </x-button.success>
                @endauth
            @endif

            <div class="flex items-center lg:ml-4">
                <x-input.label for="year" value="Tahun" />
                <x-input.select name="year" wire:model.defer="year" wire:change="$emit('reloadSubcategory', {{ $category->id }})" wire:ignore class="ml-2">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        @if ($table==false)
            @forelse ($subcategories as $subcategory)
                <div class="col-span-1">
                    <div x-data="{ open: false }" class="flex justify-center relative">
                        <x-button.white class="w-full py-6" @click="open = true">
                            <p class="font-bold">{{ $subcategory->name }}</p>
                            <img src="{{ asset('storage/images/subcategory/'.$subcategory->id.'.png') }}" alt="."
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
                                    wire:click="$emit('openModal', 'subcategory-delete-modal', {{ json_encode(['id' => $subcategory->id]) }})">Hapus</x-button.error></li>
                            </ul>
                        @endauth
                    </div>
                </div>
            @empty
                <p class="font-bold text-center mb-0">Belum ada subkategori.</p>
            @endforelse
        @else
            @if (!empty($subcategories))
                <div class="flex flex-col col-span-4">
                    <div class="overflow-x-auto">
                        <div class="inline-block py-2 min-w-full px-2">
                            <div class="overflow-hidden shadow-md sm:rounded-lg">
                                <table class="table-auto min-w-full overflow-x-auto">
                                    <thead class="bg-gray-100 rounded-t-md">
                                        <tr class="flex pr-4">
                                            <th scope='col' class='text-xs font-medium tracking-wider text-center text-gray-700 w-20 py-3 px-6 uppercase'>No</th>
                                            <th scope='col' class='grow-1 text-xs font-medium tracking-wider text-center text-gray-700 w-72 py-3 px-6 uppercase'>Desa</th>
                                            @foreach ($subcategories as $subcategory)
                                                <th scope='col' class='text-xs font-medium tracking-wider text-center text-gray-700 w-28 py-3 px-6 uppercase'>{{ $subcategory->name }}</th>
                                            @endforeach
                                            @auth
                                                <th scope='col' class='text-xs font-medium tracking-wider text-center text-gray-700 w-28 py-3 px-6 uppercase'>Action</th>
                                            @endauth
                                        </tr>
                                    </thead>
                                    <tbody class="flex flex-col max-h-80 overflow-y-auto">
                                        @foreach ($villages as $i => $v)
                                            <tr class="flex bg-white border-b w-full">
                                                <td class="text-sm text-gray-500 text-center whitespace-nowrap border-r w-20 py-4 px-6">{{ $i+1 }}</td>
                                                <td class="grow-1 text-sm text-gray-500 font-bold whitespace-nowrap border-r w-72 py-4 px-6">{{ $v->name }}</td>
                                                @foreach ($subcategories as $subcategory)
                                                    <td class="text-sm text-gray-500 text-right whitespace-nowrap border-r w-28 py-4 px-6">
                                                        @php
                                                            $value = $subcategory->bankers()
                                                                                ->where('village_id', $v->id)
                                                                                ->where('year', $year)
                                                                                ->first();
                                                            $value = !empty($value) ? $value->value : '-';
                                                            echo $value;
                                                        @endphp
                                                    </td>
                                                @endforeach
                                                @auth
                                                    <td class="text-sm text-gray-500 text-center whitespace-nowrap border-r w-28 py-4 px-6">
                                                        <x-badge.success class="cursor-pointer"
                                                            wire:click="$emit('openModal', 'bankers-modal', {{ json_encode([
                                                                'villageId' => $v->id,
                                                                'categoryId' => $category->id,
                                                                'year' => $year,
                                                            ]) }})">edit</x-badge.success>
                                                    </td>
                                                @endauth
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="font-bold text-center mb-0">Belum ada subkategori.</p>
            @endif
        @endif
    </div>
    @if($table==false)
        <div class="flex justify-center h-72 lg:h-96 mt-8">
            <livewire:livewire-pie-chart :pie-chart-model="$chart" />
        </div>
    @endif

    @auth
        <x-button.success class="mt-10" wire:click="$emit('openModal', 'subcategory-modal', {{ json_encode(['id' => '', 'categoryId' => $category->id]) }})">+ Subategori</x-button.success>
    @endauth
</x-card.full>

<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\Village;
use Exception;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class VillageModal extends ModalComponent
{
    public $village;
    public $districts;

    protected $rules = [
        'village.id_desa' => 'required|numeric|digits:10|unique:villages,id_desa',
        'village.name' => 'required|max:50|unique:villages,name',
        'village.code' => 'required|integer',
        'village.district_id' => 'required|exists:districts,id',
    ];

    public function mount($id) {
        $this->village = Village::whereId($id)->first() ?? new Village();
        $this->districts = District::get();
    }

    public function saveVillage() {
        $this->validate();
        try {
            $this->village->save();
            $this->emit('success', 'Desa disimpan');
            $this->emitTo('village', 'reloadVillage');
        } catch (Exception $e) {
            $this->emit('error', 'Gagal menyimpan');
        }
        $this->emit('closeModal');
    }

    public function render()
    {
        return view('livewire.village-modal');
    }
}

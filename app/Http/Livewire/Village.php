<?php

namespace App\Http\Livewire;

use App\Models\Village as ModelsVillage;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Village extends ModalComponent
{
    public $villages;

    protected $listeners = [
        'reloadVillage' => 'reload',
    ];

    public function mount() {
        $this->villages = ModelsVillage::get();
    }

    public function reload() {
        $this->villages = ModelsVillage::get();
    }

    public function render()
    {
        return view('livewire.village');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Subcategory as ModelsSubcategory;
use App\Models\Village;
use Livewire\Component;

class Subcategory extends Component
{
    public $category;
    public $subcategories;
    public $villages;
    public $village;
    public $villageId;
    public $year;

    protected $listeners = [
        'reloadSubcategory' => 'reload',
    ];

    public function mount($categoryId) {
        $this->category = Category::whereId($categoryId)->first();
        $this->village = Village::first();
        $this->villageId = $this->village->id;
        $this->villages = Village::get();
        $this->year = '2021';
        $this->subcategories = ModelsSubcategory::where('category_id', $this->category->id)->get();
    }

    public function reload($categoryId) {
        $this->category = Category::whereId($categoryId)->first();
        $this->subcategories = ModelsSubcategory::where('category_id', $this->category->id)->get();
    }

    public function changeVillage() {
        $this->village = Village::whereId($this->villageId)->first();
        $this->reload($this->category->id);
    }

    public function render()
    {
        return view('livewire.subcategory');
    }
}

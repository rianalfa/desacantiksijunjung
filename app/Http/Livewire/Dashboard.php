<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class Dashboard extends Component
{
    public $categories;
    public $selectedCategoryId;

    protected $listeners = [
        'reloadDashboard' => 'reload',
    ];

    public function mount() {
        $this->categories = Category::get();
        $this->selectedCategoryId = !empty($this->categories[0]) ? $this->categories[0]->id : '';
    }

    public function reload() {
        $this->categories = Category::get();
    }

    public function changeCategory($id) {
        $this->selectedCategoryId = $id;
        $this->emitTo('subcategory', 'reloadSubcategory', $id);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

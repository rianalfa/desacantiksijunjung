<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Asantibanez\LivewireCharts\Models\PieChartModel;
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
    public $table;
    public $changeChart;
    private $chart;

    protected $listeners = [
        'reloadSubcategory' => 'reload',
        'changeTable' => 'changeTable',
    ];

    public function mount($categoryId) {
        $this->category = Category::whereId($categoryId)->first();
        $this->village = Village::first();
        $this->villageId = $this->village->id;
        $this->villages = Village::get();
        $this->year = '2021';
        $this->table = true;
        $this->subcategories = ModelsSubcategory::where('category_id', $this->category->id)->get();
        $this->changeChart = true;
    }

    public function reload($categoryId) {
        $this->category = Category::whereId($categoryId)->first();
        $this->subcategories = ModelsSubcategory::where('category_id', $this->category->id)->get();
        $this->buildChart();
    }

    public function buildChart() {
        $this->chart = new PieChartModel();
        $this->chart
            ->setAnimated(true)
            ->withLegend();

        foreach ($this->subcategories as $subcategory) {
            $banker = $subcategory->bankers()
                                ->where('village_id', $this->village->id)
                                ->where('year', $this->year)
                                ->first();
            $value = (!empty($banker) && !empty($banker->value)) ? $banker->value : 0;

            $color = dechex(rand(0x000000, 0xFFFFFF));
            $this->chart->addSlice($subcategory->name, (int)$value, '#'.$color);
        }
    }

    public function changeVillage() {
        $this->village = Village::whereId($this->villageId)->first();
        $this->reload($this->category->id);
    }

    public function changeTable($bool) {
        $this->table = $bool;
    }

    public function render()
    {
        $this->reload($this->category->id);
        return view('livewire.subcategory', ['chart' => $this->chart]);
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Banker;
use App\Models\Subcategory;
use App\Models\Village;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class BankersExcelModal extends ModalComponent
{
    use WithFileUploads;

    public $csv;
    public $year;

    protected $rules = [
        'csv' => 'file|max:10000|mimes:csv',
    ];

    public function mount($year) {
        $this->year = $year;
    }

    public function saveBankers() {
        $this->validate();
        try {
            $this->csv->storeAs('', 'bankers.csv');
            $lines = Storage::get('bankers.csv');
            $lines = explode("\n", str_replace("\r", "", $lines));
            $header = explode(',', $lines[0]);

            for ($i=1; $i<sizeof($lines); $i++) {
                $line = explode(',', $lines[$i]);
                $village = Village::where('name', $line[0])->first();
                for ($j=1; $j<sizeof($header); $j++) {
                    $subcategory = Subcategory::where('name', $header[$j])->first();
                    $banker = Banker::where('village_id', $village->id)
                                    ->where('subcategory_id', $subcategory->id)
                                    ->where('year', $this->year)
                                    ->first() ?? new Banker();

                    $banker->village_id = $village->id;
                    $banker->subcategory_id = $subcategory->id;
                    $banker->year = $this->year;
                    $banker->value = $line[$j];
                    $banker->save();
                }
            }

            Storage::delete('bankers.csv');

            $this->emit('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            $this->emit('error', 'Data gagal disimpan');
        }
    }

    public function render()
    {
        return view('livewire.bankers-excel-modal');
    }
}

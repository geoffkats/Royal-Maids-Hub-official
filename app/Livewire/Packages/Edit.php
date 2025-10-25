<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    #[Layout('components.layouts.app')]
    #[Title('Edit Package')]
    
    public Package $package;
    
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('required|string|max:255')]
    public $tier = '';
    
    #[Validate('required|numeric|min:0')]
    public $base_price = '';
    
    #[Validate('required|integer|min:1')]
    public $base_family_size = 3;
    
    #[Validate('required|numeric|min:0')]
    public $additional_member_cost = 35000;
    
    #[Validate('required|integer|min:1')]
    public $training_weeks = '';
    
    public $training_includes = [];
    public $newTraining = '';
    
    #[Validate('required|integer|min:0')]
    public $backup_days_per_year = '';
    
    #[Validate('required|integer|min:0')]
    public $free_replacements = '';
    
    #[Validate('required|integer|min:0')]
    public $evaluations_per_year = '';
    
    public $features = [];
    public $newFeature = '';
    
    #[Validate('boolean')]
    public $is_active = true;
    
    #[Validate('required|integer|min:0')]
    public $sort_order = 1;

    public function mount(Package $package): void
    {
        $this->authorize('update', $package);
        
        $this->package = $package;
        $this->name = $package->name;
        $this->tier = $package->tier;
        $this->base_price = $package->base_price;
        $this->base_family_size = $package->base_family_size;
        $this->additional_member_cost = $package->additional_member_cost;
        $this->training_weeks = $package->training_weeks;
        $this->training_includes = $package->training_includes ?? [];
        $this->backup_days_per_year = $package->backup_days_per_year;
        $this->free_replacements = $package->free_replacements;
        $this->evaluations_per_year = $package->evaluations_per_year;
        $this->features = $package->features ?? [];
        $this->is_active = $package->is_active;
        $this->sort_order = $package->sort_order;
    }

    public function addTraining()
    {
        if (trim($this->newTraining)) {
            $this->training_includes[] = trim($this->newTraining);
            $this->newTraining = '';
        }
    }

    public function removeTraining($index)
    {
        unset($this->training_includes[$index]);
        $this->training_includes = array_values($this->training_includes);
    }

    public function addFeature()
    {
        if (trim($this->newFeature)) {
            $this->features[] = trim($this->newFeature);
            $this->newFeature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate();

        $this->package->update([
            'name' => $this->name,
            'tier' => $this->tier,
            'base_price' => $this->base_price,
            'base_family_size' => $this->base_family_size,
            'additional_member_cost' => $this->additional_member_cost,
            'training_weeks' => $this->training_weeks,
            'training_includes' => $this->training_includes,
            'backup_days_per_year' => $this->backup_days_per_year,
            'free_replacements' => $this->free_replacements,
            'evaluations_per_year' => $this->evaluations_per_year,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ]);

        session()->flash('message', 'Package updated successfully!');

        return redirect()->route('packages.index');
    }

    public function render()
    {
        return view('livewire.packages.edit');
    }
}

<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Layout('components.layouts.app')]
    #[Title('Subscription Packages')]
    
    public $search = '';
    public $showInactive = false;

    /**
     * Authorize the user.
     */
    public function mount(): void
    {
        $this->authorize('viewAny', Package::class);
    }

    /**
     * Get filtered packages.
     */
    public function getPackagesProperty()
    {
        $query = Package::query();

        // For clients, only show active packages
        if (auth()->user()->role !== 'admin') {
            $query->where('is_active', true);
        } elseif (!$this->showInactive) {
            // For admins, respect the filter
            $query->where('is_active', true);
        }

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('tier', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('sort_order')->get();
    }

    /**
     * Toggle package active status.
     */
    public function toggleActive($packageId): void
    {
        $package = Package::findOrFail($packageId);
        $this->authorize('update', $package);
        
        $package->update(['is_active' => !$package->is_active]);
        
        session()->flash('message', "Package {$package->name} " . ($package->is_active ? 'activated' : 'deactivated') . " successfully.");
    }

    /**
     * Delete a package.
     */
    public function delete($packageId): void
    {
        $package = Package::findOrFail($packageId);
        $this->authorize('delete', $package);

        // Check if package has bookings
        if ($package->bookings()->count() > 0) {
            session()->flash('error', "Cannot delete {$package->name} - it has associated bookings.");
            return;
        }

        $package->delete();
        session()->flash('message', "Package {$package->name} deleted successfully.");
    }

    public function render()
    {
        return view('livewire.packages.index', [
            'packages' => $this->packages,
            'isAdmin' => auth()->user()->role === 'admin',
        ]);
    }
}

<?php

namespace App\Livewire\CRM\Tags;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CRM\Tag;

class Index extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $perPage = 25;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Bulk actions
    public $selectedTags = [];
    public $selectPage = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 25],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->perPage = request('perPage', 25);
        $this->sortBy = request('sortBy', 'name');
        $this->sortDirection = request('sortDirection', 'asc');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selectedTags = $this->tags->pluck('id')->toArray();
        } else {
            $this->selectedTags = [];
        }
    }

    public function updatedSelectedTags()
    {
        $this->selectPage = false;
    }

    public function clearSelection()
    {
        $this->selectedTags = [];
        $this->selectPage = false;
    }

    public function deleteSelected()
    {
        if (empty($this->selectedTags)) {
            return;
        }

        Tag::whereIn('id', $this->selectedTags)->delete();
        
        $this->selectedTags = [];
        $this->selectPage = false;
        
        session()->flash('message', 'Selected tags deleted successfully.');
    }

    public function getTagsProperty()
    {
        return Tag::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount(['leads', 'opportunities'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.c-r-m.tags.index');
    }
}

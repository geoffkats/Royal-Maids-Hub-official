<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $subscription_status = null;

    #[Url]
    public ?string $subscription_tier = null;

    #[Url]
    public int $perPage = 15;

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    // Deletion confirmation state
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public ?string $deleteName = null;

    public function mount(): void
    {
        $this->authorize('viewAny', Client::class);
    }

    public function updating($name, $value): void
    {
        // Reset to first page whenever filters or search change
        if (in_array($name, ['search', 'subscription_status', 'subscription_tier', 'perPage', 'sortBy', 'sortDirection'], true)) {
            $this->resetPage();
        }
    }

    public function getSubscriptionStatuses(): array
    {
        return ['active', 'expired', 'pending', 'cancelled'];
    }

    public function getSubscriptionTiers(): array
    {
        return ['basic', 'premium', 'enterprise'];
    }

    public function render()
    {
        $clients = $this->queryClients();

        return view('livewire.clients.index', [
            'clients' => $clients,
            'statusOptions' => $this->getSubscriptionStatuses(),
            'tierOptions' => $this->getSubscriptionTiers(),
        ])->layout('components.layouts.app', ['title' => __('Clients')]);
    }

    protected function queryClients(): LengthAwarePaginator
    {
        $term = trim($this->search);
        $canViewIdentity = Gate::allows('viewSensitiveIdentity');

        return Client::query()
            ->with('user')
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('contact_person', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('city', 'like', $like);
                });
            })
            ->when($term !== '' && $canViewIdentity, function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';
                $q->orWhere('identity_number', 'like', $like);
            })
            ->when(!empty($this->subscription_status), fn($q) => $q->where('subscription_status', $this->subscription_status))
            ->when(!empty($this->subscription_tier), fn($q) => $q->where('subscription_tier', $this->subscription_tier))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function delete(int $clientId): void
    {
        $client = Client::with('user')->findOrFail($clientId);
        $this->authorize('delete', $client);

        // Soft delete the client record to avoid hard-deleting related users.
        $client->delete();

        session()->flash('success', __('Client deleted successfully.'));
        // Reset to first page if current page becomes empty after deletion
        if ($this->getPage() > 1 && $this->queryClients()->isEmpty()) {
            $this->resetPage();
        }
    }

    public function confirmDelete(int $clientId): void
    {
        $client = Client::with('user')->findOrFail($clientId);
        $this->deleteId = $client->id;
        $this->deleteName = trim(($client->contact_person ?? '') . ' (' . ($client->user->email ?? '') . ')');
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->reset('showDeleteModal', 'deleteId', 'deleteName');
    }

    public function deleteConfirmed(): void
    {
        if ($this->deleteId) {
            $this->delete($this->deleteId);
        }
        $this->cancelDelete();
    }
}

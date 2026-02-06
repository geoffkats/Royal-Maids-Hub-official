<?php

namespace App\Livewire\Admin\Trainers;

use App\Models\Trainer;
use App\Models\TrainerSidebarPermission;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;

class ManagePermissions extends Component
{
    use AuthorizesRequests;

    #[Url]
    public string $search = '';

    /** @var array<int, array<string, bool>> */
    public array $permissions = [];

    public string $message = '';

    public function mount(): void
    {
        $user = auth()->user();

        if (! $user || $user->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $this->authorize('viewAny', Trainer::class);
        $this->loadPermissions();
    }

    public function updating($name, $value): void
    {
        if ($name === 'search') {
            $this->message = '';
        }
    }

    private function loadPermissions(): void
    {
        $trainers = $this->getTrainers();
        $this->permissions = [];

        foreach ($trainers as $trainer) {
            $this->permissions[$trainer->id] = [];

            foreach (TrainerSidebarPermission::getAllItems() as $itemKey => $itemData) {
                $this->permissions[$trainer->id][$itemKey] = $trainer->hasAccessTo($itemKey);
            }
        }
    }

    public function savePermissions(): void
    {
        $trainers = $this->getTrainers();

        foreach ($trainers as $trainer) {
            if (! isset($this->permissions[$trainer->id])) {
                continue;
            }

            $trainerPermissions = $this->permissions[$trainer->id];

            foreach (TrainerSidebarPermission::getAllItems() as $itemKey => $itemData) {
                $hasPermission = $trainerPermissions[$itemKey] ?? false;
                $currentPermission = $trainer->sidebarPermissions()
                    ->where('sidebar_item', $itemKey)
                    ->first();

                if ($hasPermission && ! $currentPermission) {
                    TrainerSidebarPermission::create([
                        'trainer_id' => $trainer->id,
                        'sidebar_item' => $itemKey,
                        'granted_at' => now(),
                    ]);
                } elseif (! $hasPermission && $currentPermission) {
                    $currentPermission->delete();
                }
            }
        }

        $this->message = 'Permissions updated successfully!';
        $this->dispatch('permissions-updated');
    }

    /**
     * Get trainers matching search criteria.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Trainer>
     */
    private function getTrainers()
    {
        $term = trim($this->search);

        $query = Trainer::query()->with('user', 'sidebarPermissions');

        if ($term !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
            $query->whereHas('user', function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            })->orWhere('specialization', 'like', $like);
        }

        return $query->orderBy('id')->get();
    }

    /**
     * Get non-trainer staff for visibility on this page.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>
     */
    private function getNonTrainerUsers()
    {
        $term = trim($this->search);

        $query = User::query()
            ->whereIn('role', [
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
                User::ROLE_OPERATIONS_MANAGER,
                User::ROLE_FINANCE_OFFICER,
                User::ROLE_CUSTOMER_SUPPORT,
            ]);

        if ($term !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        return $query->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.trainers.manage-permissions', [
            'trainers' => $this->getTrainers(),
            'nonTrainerUsers' => $this->getNonTrainerUsers(),
            'availableItems' => TrainerSidebarPermission::getAllItems(),
            'itemsBySection' => $this->groupItemsBySection(),
        ]);
    }

    /**
     * Group sidebar items by section for better UI organization.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    private function groupItemsBySection(): array
    {
        $items = TrainerSidebarPermission::getAllItems();

        return [
            'Management' => [
                TrainerSidebarPermission::ITEM_MAIDS => $items[TrainerSidebarPermission::ITEM_MAIDS],
                TrainerSidebarPermission::ITEM_TRAINERS => $items[TrainerSidebarPermission::ITEM_TRAINERS],
                TrainerSidebarPermission::ITEM_CLIENTS => $items[TrainerSidebarPermission::ITEM_CLIENTS],
                TrainerSidebarPermission::ITEM_BOOKINGS => $items[TrainerSidebarPermission::ITEM_BOOKINGS],
            ],
            'Training & Development' => [
                TrainerSidebarPermission::ITEM_MY_PROGRAMS => $items[TrainerSidebarPermission::ITEM_MY_PROGRAMS],
                TrainerSidebarPermission::ITEM_MY_EVALUATIONS => $items[TrainerSidebarPermission::ITEM_MY_EVALUATIONS],
                TrainerSidebarPermission::ITEM_DEPLOYMENTS => $items[TrainerSidebarPermission::ITEM_DEPLOYMENTS],
                TrainerSidebarPermission::ITEM_TRAINER_PERMISSIONS => $items[TrainerSidebarPermission::ITEM_TRAINER_PERMISSIONS],
            ],
            'Analytics & Reports' => [
                TrainerSidebarPermission::ITEM_REPORTS => $items[TrainerSidebarPermission::ITEM_REPORTS],
                TrainerSidebarPermission::ITEM_KPI_DASHBOARD => $items[TrainerSidebarPermission::ITEM_KPI_DASHBOARD],
                TrainerSidebarPermission::ITEM_WEEKLY_BOARDS_REVIEW => $items[TrainerSidebarPermission::ITEM_WEEKLY_BOARDS_REVIEW],
            ],
            'Support & Tickets' => [
                TrainerSidebarPermission::ITEM_CONTACT_INQUIRIES => $items[TrainerSidebarPermission::ITEM_CONTACT_INQUIRIES],
                TrainerSidebarPermission::ITEM_TICKETS => $items[TrainerSidebarPermission::ITEM_TICKETS],
                TrainerSidebarPermission::ITEM_TICKETS_INBOX => $items[TrainerSidebarPermission::ITEM_TICKETS_INBOX],
                TrainerSidebarPermission::ITEM_TICKET_ANALYTICS => $items[TrainerSidebarPermission::ITEM_TICKET_ANALYTICS],
            ],
            'CRM' => [
                TrainerSidebarPermission::ITEM_CRM_PIPELINE => $items[TrainerSidebarPermission::ITEM_CRM_PIPELINE],
                TrainerSidebarPermission::ITEM_CRM_LEADS => $items[TrainerSidebarPermission::ITEM_CRM_LEADS],
                TrainerSidebarPermission::ITEM_CRM_OPPORTUNITIES => $items[TrainerSidebarPermission::ITEM_CRM_OPPORTUNITIES],
                TrainerSidebarPermission::ITEM_CRM_ACTIVITIES => $items[TrainerSidebarPermission::ITEM_CRM_ACTIVITIES],
                TrainerSidebarPermission::ITEM_CRM_SETTINGS => $items[TrainerSidebarPermission::ITEM_CRM_SETTINGS],
                TrainerSidebarPermission::ITEM_CRM_REPORTS => $items[TrainerSidebarPermission::ITEM_CRM_REPORTS],
            ],
            'System' => [
                TrainerSidebarPermission::ITEM_COMPANY_SETTINGS => $items[TrainerSidebarPermission::ITEM_COMPANY_SETTINGS],
                TrainerSidebarPermission::ITEM_SCHEDULE => $items[TrainerSidebarPermission::ITEM_SCHEDULE],
                TrainerSidebarPermission::ITEM_WEEKLY_BOARD => $items[TrainerSidebarPermission::ITEM_WEEKLY_BOARD],
            ],
            'Business' => [
                TrainerSidebarPermission::ITEM_PACKAGES => $items[TrainerSidebarPermission::ITEM_PACKAGES],
            ],
        ];
    }
}
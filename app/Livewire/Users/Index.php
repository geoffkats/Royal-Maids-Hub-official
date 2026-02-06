<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $roleFilter = '';

    #[Url]
    public string $verificationFilter = 'all';

    #[Url]
    public int $perPage = 15;

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeactivateModal = false;
    public bool $showDeleteModal = false;
    public bool $showResetModal = false;

    public ?int $editingUserId = null;
    public ?int $actionUserId = null;
    public ?string $actionUserName = null;
    public bool $actionUserActive = true;
    public string $name = '';
    public string $email = '';
    public string $role = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $emailVerified = true;
    public string $resetPassword = '';
    public string $resetPassword_confirmation = '';

    public function mount(): void
    {
        $this->authorizeAccess();
        $this->role = User::ROLE_CUSTOMER_SUPPORT;
    }

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'roleFilter', 'verificationFilter', 'perPage', 'sortBy', 'sortDirection'], true)) {
            $this->resetPage();
        }
    }

    /**
     * Restrict user management to super admins only.
     */
    private function authorizeAccess(): void
    {
        Gate::authorize('manageUsers');
    }

    public function openCreateModal(): void
    {
        $this->authorizeAccess();
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $userId): void
    {
        $this->authorizeAccess();

        $user = User::query()->findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->emailVerified = (bool) $user->email_verified_at;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeactivateModal = false;
        $this->showDeleteModal = false;
        $this->showResetModal = false;
        $this->resetForm();
    }

    public function createUser(): void
    {
        $this->authorizeAccess();
        $this->editingUserId = null;

        $validated = $this->validate();

        if ($validated['role'] === User::ROLE_SUPER_ADMIN && $this->superAdminExists()) {
            $this->addError('role', __('Only one super admin account is allowed.'));
            return;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => $validated['emailVerified'] ? now() : null,
        ]);

        session()->flash('success', __('User created successfully.'));
        $this->closeModal();
    }

    /**
     * Protect against self-demotion while editing your own account.
     */
    public function updateUser(): void
    {
        $this->authorizeAccess();

        if (!$this->editingUserId) {
            return;
        }

        $validated = $this->validate();
        $user = User::query()->findOrFail($this->editingUserId);

        if ($validated['role'] === User::ROLE_SUPER_ADMIN && $this->superAdminExists($user->id)) {
            $this->addError('role', __('Only one super admin account is allowed.'));
            return;
        }

        if ($user->id === auth()->id() && $user->role !== $validated['role'] && $validated['role'] !== User::ROLE_SUPER_ADMIN) {
            $this->addError('role', __('You cannot change your own role.'));
            return;
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'email_verified_at' => $validated['emailVerified'] ? now() : null,
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        session()->flash('success', __('User updated successfully.'));
        $this->closeModal();
    }

    protected function rules(): array
    {
        $emailRule = Rule::unique('users', 'email');

        if ($this->editingUserId) {
            $emailRule = $emailRule->ignore($this->editingUserId);
        }

        $passwordRules = $this->editingUserId
            ? ['nullable', 'string', 'min:8', 'confirmed']
            : ['required', 'string', 'min:8', 'confirmed'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'role' => ['required', Rule::in(User::roles())],
            'password' => $passwordRules,
            'emailVerified' => ['boolean'],
        ];
    }

    protected function resetPasswordRules(): array
    {
        return [
            'resetPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function openDeactivateModal(int $userId): void
    {
        $this->authorizeAccess();

        $user = User::query()->findOrFail($userId);

        if ($user->role === User::ROLE_SUPER_ADMIN) {
            $this->addError('action', __('Super admin accounts cannot be deactivated.'));
            return;
        }

        if ($user->id === auth()->id()) {
            $this->addError('action', __('You cannot deactivate your own account.'));
            return;
        }

        $this->actionUserId = $user->id;
        $this->actionUserName = $user->name;
        $this->actionUserActive = (bool) $user->is_active;
        $this->showDeactivateModal = true;
    }

    public function toggleActive(): void
    {
        $this->authorizeAccess();

        if (!$this->actionUserId) {
            return;
        }

        $user = User::query()->findOrFail($this->actionUserId);

        if ($user->role === User::ROLE_SUPER_ADMIN) {
            $this->addError('action', __('Super admin accounts cannot be deactivated.'));
            return;
        }

        if ($user->id === auth()->id()) {
            $this->addError('action', __('You cannot deactivate your own account.'));
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        session()->flash('success', $user->is_active
            ? __('User reactivated successfully.')
            : __('User deactivated successfully.')
        );

        $this->closeModal();
    }

    public function openDeleteModal(int $userId): void
    {
        $this->authorizeAccess();

        $user = User::query()->findOrFail($userId);

        if ($user->role === User::ROLE_SUPER_ADMIN) {
            $this->addError('action', __('Super admin accounts cannot be deleted.'));
            return;
        }

        if ($user->id === auth()->id()) {
            $this->addError('action', __('You cannot delete your own account.'));
            return;
        }

        $this->actionUserId = $user->id;
        $this->actionUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        $this->authorizeAccess();

        if (!$this->actionUserId) {
            return;
        }

        $user = User::query()->findOrFail($this->actionUserId);

        if ($user->role === User::ROLE_SUPER_ADMIN) {
            $this->addError('action', __('Super admin accounts cannot be deleted.'));
            return;
        }

        if ($user->id === auth()->id()) {
            $this->addError('action', __('You cannot delete your own account.'));
            return;
        }

        $user->delete();

        session()->flash('success', __('User deleted successfully.'));
        $this->closeModal();
    }

    public function openResetModal(int $userId): void
    {
        $this->authorizeAccess();

        $user = User::query()->findOrFail($userId);
        $this->actionUserId = $user->id;
        $this->actionUserName = $user->name;
        $this->resetPassword = '';
        $this->resetPassword_confirmation = '';
        $this->showResetModal = true;
    }

    public function resetUserPassword(): void
    {
        $this->authorizeAccess();

        if (!$this->actionUserId) {
            return;
        }

        $validated = $this->validate($this->resetPasswordRules());
        $user = User::query()->findOrFail($this->actionUserId);
        $user->password = Hash::make($validated['resetPassword']);
        $user->save();

        session()->flash('success', __('Password reset successfully.'));
        $this->closeModal();
    }

    protected function query(): LengthAwarePaginator
    {
        $term = trim($this->search);
        $sortBy = in_array($this->sortBy, ['name', 'email', 'role', 'created_at'], true)
            ? $this->sortBy
            : 'created_at';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return User::query()
            ->where('role', '!=', User::ROLE_CLIENT)
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            })
            ->when($this->roleFilter !== '', function ($q) {
                $q->where('role', $this->roleFilter);
            })
            ->when($this->verificationFilter === 'verified', function ($q) {
                $q->whereNotNull('email_verified_at');
            })
            ->when($this->verificationFilter === 'unverified', function ($q) {
                $q->whereNull('email_verified_at');
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function render()
    {
        $this->authorizeAccess();

        return view('livewire.users.index', [
            'users' => $this->query(),
            'totalUsers' => User::query()->where('role', '!=', User::ROLE_CLIENT)->count(),
            'roleCounts' => User::query()
                ->where('role', '!=', User::ROLE_CLIENT)
                ->select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role'),
            'roles' => $this->staffRoles(),
        ])->layout('components.layouts.app', ['title' => __('User Management')]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingUserId',
            'actionUserId',
            'actionUserName',
            'actionUserActive',
            'name',
            'email',
            'password',
            'password_confirmation',
            'resetPassword',
            'resetPassword_confirmation',
        ]);

        $this->role = User::ROLE_CUSTOMER_SUPPORT;
        $this->emailVerified = true;
    }

    /**
     * @return list<string>
     */
    private function staffRoles(): array
    {
        return array_values(array_filter(User::roles(), fn (string $role) => $role !== User::ROLE_CLIENT));
    }

    private function superAdminExists(?int $ignoreUserId = null): bool
    {
        return User::query()
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->when($ignoreUserId, fn ($q) => $q->where('id', '!=', $ignoreUserId))
            ->exists();
    }
}

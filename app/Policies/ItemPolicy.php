<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN,
            User::ROLE_FINANCE,
            User::ROLE_PROJECT_MANAGER,
        ]);
    }

    public function view(User $user, Item $item): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN,
        ]);
    }

    public function update(User $user, Item $item): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Item $item): bool
    {
        return $this->create($user);
    }
}

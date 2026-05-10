<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
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

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_FINANCE,
        ]);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_FINANCE,
        ]);
    }

    public function send(User $user, Invoice $invoice): bool
    {
        return $user->role === User::ROLE_FINANCE || $user->role === User::ROLE_SUPER_ADMIN;
    }

    public function monitor(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_FINANCE,
        ]);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_FINANCE,
        ]);
    }
}

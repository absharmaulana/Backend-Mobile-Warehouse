<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
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

    public function view(User $user, Survey $survey): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_PROJECT_MANAGER,
        ]);
    }

    public function update(User $user, Survey $survey): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_PROJECT_MANAGER,
        ]);
    }

    public function delete(User $user, Survey $survey): bool
    {
        return in_array($user->role, [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_PROJECT_MANAGER,
        ]);
    }
}

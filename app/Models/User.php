<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_FINANCE = 'finance';
    public const ROLE_PROJECT_MANAGER = 'project_manager';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
        self::ROLE_FINANCE,
        self::ROLE_PROJECT_MANAGER,
    ];

    public const PERMISSION_DASHBOARD_VIEW = 'dashboard.view';
    public const PERMISSION_ITEMS_VIEW = 'items.view';
    public const PERMISSION_ITEMS_MANAGE = 'items.manage';
    public const PERMISSION_INVOICES_VIEW = 'invoices.view';
    public const PERMISSION_INVOICES_CREATE = 'invoices.create';
    public const PERMISSION_INVOICES_UPDATE = 'invoices.update';
    public const PERMISSION_INVOICES_SEND = 'invoices.send';
    public const PERMISSION_CATEGORIES_MANAGE = 'categories.manage';
    public const PERMISSION_SUPPLIERS_MANAGE = 'suppliers.manage';
    public const PERMISSION_REPORTS_VIEW = 'reports.view';
    public const PERMISSION_ACCOUNTS_MANAGE = 'accounts.manage';
    public const PERMISSION_SURVEYS_VIEW = 'surveys.view';
    public const PERMISSION_SURVEYS_MANAGE = 'surveys.manage';
    public const PERMISSION_PROJECTS_VIEW = 'projects.view';
    public const PERMISSION_PROJECTS_MANAGE = 'projects.manage';
    public const PERMISSION_ASSIGNMENTS_MANAGE = 'assignments.manage';

    /**
     * @return array<string, list<string>>
     */
    public static function rolePermissionsMap(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => ['*'],
            self::ROLE_ADMIN => [
                self::PERMISSION_DASHBOARD_VIEW,
                self::PERMISSION_ITEMS_VIEW,
                self::PERMISSION_ITEMS_MANAGE,
                self::PERMISSION_CATEGORIES_MANAGE,
                self::PERMISSION_SUPPLIERS_MANAGE,
                self::PERMISSION_REPORTS_VIEW,
            ],
            self::ROLE_FINANCE => [
                self::PERMISSION_DASHBOARD_VIEW,
                self::PERMISSION_ITEMS_VIEW,
                self::PERMISSION_INVOICES_VIEW,
                self::PERMISSION_INVOICES_CREATE,
                self::PERMISSION_INVOICES_UPDATE,
                self::PERMISSION_INVOICES_SEND,
                self::PERMISSION_REPORTS_VIEW,
            ],
            self::ROLE_PROJECT_MANAGER => [
                self::PERMISSION_DASHBOARD_VIEW,
                self::PERMISSION_ITEMS_VIEW,
                self::PERMISSION_INVOICES_VIEW,
                self::PERMISSION_SURVEYS_VIEW,
                self::PERMISSION_SURVEYS_MANAGE,
                self::PERMISSION_PROJECTS_VIEW,
                self::PERMISSION_PROJECTS_MANAGE,
                self::PERMISSION_ASSIGNMENTS_MANAGE,
                self::PERMISSION_REPORTS_VIEW,
            ],
        ];
    }

    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Automatically hash password when it's set.
     * Works for create(), update(), forceFill(), and direct assignment.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * @return list<string>
     */
    public function permissions(): array
    {
        $permissions = self::rolePermissionsMap()[$this->role] ?? [];

        if ($permissions === ['*']) {
            return ['*'];
        }

        return array_values(array_unique($permissions));
    }

    public function hasPermission(string $permission): bool
    {
        return $this->isSuperAdmin() || in_array('*', $this->permissions(), true) || in_array($permission, $this->permissions(), true);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function dashboardKey(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'super_admin_dashboard',
            self::ROLE_ADMIN => 'admin_dashboard',
            self::ROLE_FINANCE => 'finance_dashboard',
            self::ROLE_PROJECT_MANAGER => 'project_manager_dashboard',
            default => 'default_dashboard',
        };
    }
}

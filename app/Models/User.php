<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'username',
    'first_name',
    'last_name',
    'email',
    'password',
    'keycloak_id',
    'role',
    'enabled',
])]

#[Hidden([
    'password',
    'remember_token',
])]

#[Appends([
    'full_name',
])]

/**
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string $email
 * @property string|null $keycloak_id
 * @property RoleEnum $role
 * @property bool $enabled
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    use SoftDeletes;

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => RoleEnum::class,
            'enabled' => 'boolean',
        ];
    }

    /**
     * User full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => trim(
                "{$this->first_name} {$this->last_name}"
            ),
        );
    }
}

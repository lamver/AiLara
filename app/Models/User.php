<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Wnikk\LaravelAccessRules\Models\Owner;
use Wnikk\LaravelAccessRules\Traits\HasPermissions;
use Wnikk\LaravelAccessRules\Models\Inheritance;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const STATUS_ON = 1;
    const STATUS_OFF = 0;

    /**
     * @return array
     */
    static public function getAuthors()
    {
        return self::query()->get();
    }

    /**
     * Get the parent owner in the inheritance chain of the current owner.
     *
     * @return Owner|null The parent owner if found, null otherwise.
     */
    public function getInheritanceParent(): ?Owner
    {
        return Inheritance::where('owner_id', $this->getOwner()->id)->first()?->ownerParent()->first();
    }

    static public function getAvatarUrl(User $user)
    {
        if (!isset($user->avatar)) {
            return '/images/avatar/no_avatar.png';
        }

        return $user->avatar;
    }

}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Area\Area;
use App\Models\Branch\Branch;
use App\Enums\User\UserStatus;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.

     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'is_active',
        'address',
        'phone',
        'avatars',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'avatars' => 'array',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => UserStatus::class
        ];
    }

    // protected function avatar(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value ? Storage::disk('public')->url($value) : "",
    //     );
    // }
    protected function avatars(): Attribute{
        return Attribute::make(
            get: function ($value) {
                $paths = json_decode($value, true); // تأكد أن القيمة مصفوفة
                if (!is_array($paths)) return [];
                return array_map(fn($path) => Storage::disk('public')->url($path), $paths);
            },
        );
   }
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branches');
    }


}


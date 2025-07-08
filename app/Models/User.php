<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CustomResetPassword;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Log;

/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 */

class User extends Authenticatable implements FilamentUser, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Determine if the user can access the given Filament panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Allow all users to access the panel; adjust logic as needed.
        return true;
    }

    // Custom notification reset password
    public function sendPasswordResetNotification($token): void
    {
        Log::info('Attempting to send reset email to: ' . $this->email);

        try {
            $this->notify(new CustomResetPassword($token));
            Log::info('Reset email sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send reset email: ' . $e->getMessage());
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function owner()
    {
        return $this->hasOne(Owner::class, 'user_id', 'id');
    }

    public function penghuni()
    {
        return $this->hasOne(Penghuni::class, 'user_id');
    }
}

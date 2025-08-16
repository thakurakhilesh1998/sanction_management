<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PasswordHistories;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'district',
        'block_name',
        'gp_name',
        'password_expires_at'
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
        'password_expires_at' => 'datetime',
    ];

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistories::class);
    }

    /**
     * Set the password expiration date.
     *
     * @param int $days The number of days until password expiration.
     */
    public function setPasswordExpiry(int $days = 180)
    {
        $this->password_expires_at = now()->addDays($days);
        $this->save();
    }

    /**
     * Check if the new password matches any of the last 5 used passwords.
     *
     * @param string $newPassword The new password to check.
     * @return bool
     */
    public function checkPasswordHistory(string $newPassword): bool
    {
        $recentPasswords = $this->passwordHistories()->latest()->take(5)->pluck('password');

        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($newPassword, $oldPassword)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Update the user's password with history and expiration checks.
     *
     * @param string $newPassword The new password to set.
     * @return bool
     */
    public function updatePassword(string $newPassword): bool
    {
        if ($this->checkPasswordHistory($newPassword)) {
            // Save the current password in the password history before updating
            $this->passwordHistories()->create(['password' => $this->password]);

            // Update the user's password and set the new expiration date
            $this->password = bcrypt($newPassword);
            $this->setPasswordExpiry(); // Set default expiry of 90 days
            $this->save();

            return true;
        }

        return false;
    }

}

<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'email',
        'password',
        'avatar',
        'wallet',
        'passphrase'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'passphrase',
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

    protected $appends = [
        'has_passphrase',
    ];

    public function getHasPassphraseAttribute(): bool
    {
        return (bool) $this->passphrase;
    }

    public function getAvatarAttribute($value): string
    {
        return $value
            ? asset('storage/' . $value)
            : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify((new VerifyEmailNotification)->onQueue('default'));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function exchangeTransactions()
    {
        return $this->hasMany(ExchangeTransaction::class);
    }
}

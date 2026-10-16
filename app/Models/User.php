<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function cafes()
    {
        return $this->hasMany(Cafe::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Admin dari form lama yang belum di-approve tidak dianggap admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->status === 'approved';
    }

    public function isSeller(): bool
    {
        return $this->role === 'penjual';
    }

    // Admin selalu boleh, penjual hanya setelah di-approve
    public function canAccessDashboard(): bool
    {
        return $this->isAdmin() || ($this->isSeller() && $this->status === 'approved');
    }

    public function ownsCafe(Cafe $cafe): bool
    {
        return $this->isAdmin() || $cafe->user_id === $this->id;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'pdf_file',
        'status',
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
    ];
}

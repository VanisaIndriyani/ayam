<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Role;
use App\Models\Cart;
use App\Models\Order;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',        // string: admin/user
        'address',
    ];

    /**
     * Kolom yang disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi: User → Role (many-to-one)
     */
    /* No role relationship as it conflicts with role column */

    /**
     * Relasi: User → Cart (one-to-one)
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Relasi: User → Orders (one-to-many)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi: User → Reviews (one-to-many)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Helper untuk pengecekan role
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}

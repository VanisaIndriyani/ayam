<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional jika nama tabel 'roles')
     */
    protected $table = 'roles';

    /**
     * Kolom yang bisa diisi
     */
    protected $fillable = [
        'name', // admin, user, dll
    ];

    /**
     * Relasi Role → User (1 Role punya banyak User)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    /**
     * Auto generate slug saat create/update
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Relasi ke produk
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Parent Category (jika kategori ini adalah subkategori)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Children Category (jika kategori ini punya subkategori)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}

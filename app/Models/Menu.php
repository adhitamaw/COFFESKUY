<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $fillable = ['nama', 'harga', 'gambar', 'user_id', 'cafe_id'];

    public function cafes()
    {
        // return $this->belongsToMany(Cafe::class);  kurang tepat penggunaanya
        // return $this->hasMany(Cafe::class);  kurang tepat juga
        return $this->belongsTo(Cafe::class);  // 1 menu itu punya hubungan dengan 1 cafe
    }
}

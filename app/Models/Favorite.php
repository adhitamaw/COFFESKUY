<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorite';
    protected $fillable = ['user_id', 'cafe_id'];

    //punya 1 cafe_id dan 1 user_id

    public function cafe()
    {
        return $this->belongsTo(Cafe::class,'cafe_id'); 
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cafe extends Model
{
    use HasFactory;

    protected $table = 'cafe';
    protected $fillable = ['nama','alamat','gambar','content','latitude','longitude']; 

    public function reviews()
    {
        return $this->hasMany(Review::class, 'cafe_id');   // relasi one to many dengan review
    }

    public function user()
    {
        return $this->belongsTo(User::class);   // 1 cafe punya 1 user , ketergantungan , jadi setiap 1 cafe pasti punya 1 owner
    }

    public function menus()
    {
        return $this->hasMany(Menu::class); // relasi one to many dengan menu
    }
    
    public function favorites() {
        return $this->hasMany(Favorite::class, 'cafe_id'); //relasi one to many dengan favorite
    }

}

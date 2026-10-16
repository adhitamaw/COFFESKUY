<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    use HasFactory;

    protected $table = 'cafe';
    protected $fillable = ['nama', 'alamat', 'gambar', 'content', 'latitude', 'longitude', 'user_id'];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'cafe_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'cafe_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'cafe_id');
    }

    // Tambahkan kolom reviews_avg_rating & reviews_count ke query
    public function scopeWithRating($query)
    {
        return $query->withAvg('reviews', 'rating')->withCount('reviews');
    }

    public function getRatingAttribute(): ?float
    {
        $avg = $this->reviews_avg_rating ?? $this->reviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : null;
    }

    public function hasLocation(): bool
    {
        return is_numeric($this->latitude) && is_numeric($this->longitude);
    }

    // Hapus cafe beserta semua data yang bergantung padanya
    public function deleteWithRelations(): void
    {
        $this->menus()->delete();
        $this->reviews()->delete();
        $this->favorites()->delete();
        $this->delete();
    }
}

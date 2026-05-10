<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    /**
     * Get the image URL for the category.
     *
     * @param  string  $image
     * @return string
     */
    public function getImageAttribute($image)
    {
        return asset('storage/categories/' . $image);
    }
}

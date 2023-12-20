<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Image;
use App\Models\Tenant\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'combinations', 'brand', 'slug', 'precision', 'price', 'category_id', 'stock', 'compareAtPrice', 'taxed', 'visible', 'tags', 'lowstockthreshold'];
    
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
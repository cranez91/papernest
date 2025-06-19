<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'brand',
        'cost',
        'price',
        'photo',
        'attachment_file_name',
        'category_id',
        'stock'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::deleting(function ($product) {
            if ($product->photo) {
                //Storage::disk('public')->delete("products/{$product->image}");
                Storage::disk('public')->delete("{$product->photo}");
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

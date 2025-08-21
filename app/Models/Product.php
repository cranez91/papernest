<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $appends = [
        'category_name',
        'short_name',
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
                // Storage::disk('public')->delete("{$product->photo}");
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeBasicInfo($query) {
        return $query->select([
            'sku',
            'name',
            'slug',
            'brand',
            'price',
            'stock',
            'photo',
            'category_id',
            'attachment_file_name',
            'description',
        ]);
    }

    public function scopeActiveItems($query) 
    {
        return $query->where('status', 'active');
    }

    public function scopeFilterByName($query, $filter) 
    {
        $keywords = explode(' ', $filter);
        
        return $query->where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        });
    }

    public function getCategoryNameAttribute()
    {
        return $this->category?->name;
    }

    public function getShortNameAttribute()
    {
        return Str::limit($this->name, 22, '...');
    }
}

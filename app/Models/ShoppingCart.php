<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ShoppingCart extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;  // ⚡ deactivate autoincrement

    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class);
    }
}

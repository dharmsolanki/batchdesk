<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use App\Models\SupplierProduct;

class Supplier extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(SupplierProduct::class);
    }
    public function coas()
    {
        return $this->hasManyThrough(SupplierCoa::class, SupplierProduct::class);
    }
}

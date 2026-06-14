<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function sales()
    {
        return $this->hasMany(Sale::class)->latest();
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function specParams()
    {
        return $this->hasMany(SpecParam::class)->orderBy('sort')->orderBy('id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}

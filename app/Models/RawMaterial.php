<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function lots()
    {
        return $this->hasMany(MaterialLot::class)->orderBy('expiry');
    }
}

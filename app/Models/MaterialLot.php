<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class MaterialLot extends Model
{
    use BelongsToCompany;
    protected $guarded = [];
    protected $casts = ['expiry' => 'date', 'received_date' => 'date'];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}

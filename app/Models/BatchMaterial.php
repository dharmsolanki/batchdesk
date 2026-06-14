<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchMaterial extends Model
{
    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function materialLot()
    {
        return $this->belongsTo(MaterialLot::class);
    }
}

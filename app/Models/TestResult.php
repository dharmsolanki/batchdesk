<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $guarded = [];
    protected $casts = ['pass' => 'boolean'];

    public function specParam()
    {
        return $this->belongsTo(SpecParam::class);
    }
}

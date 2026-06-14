<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use BelongsToCompany;
    protected $guarded = [];
    protected $casts = ['mfg_date' => 'date', 'exp_date' => 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->hasMany(BatchMaterial::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function getAllPassedAttribute(): bool
    {
        $params = $this->product->specParams()->count();
        if ($params === 0) return false;
        $results = $this->testResults;
        return $results->count() >= $params && $results->every(fn ($r) => $r->pass);
    }
}

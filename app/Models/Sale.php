<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function getPendingAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->paid_amount);
    }
}

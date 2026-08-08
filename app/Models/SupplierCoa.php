<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCoa extends Model
{
    protected $guarded = [];
    protected $casts = [
        'received_date' => 'date',
        'expiry_date'   => 'date',
        'parameters'    => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function supplierProduct()
    {
        return $this->belongsTo(SupplierProduct::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->coa_status) {
            'pending'  => 'text-muted',
            'received' => 'text-brand',
            'verified' => 'text-pass',
            default    => 'text-muted',
        };
    }
}

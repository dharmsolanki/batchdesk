<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use BelongsToCompany;
    protected $guarded = [];
    protected $casts = ['valid_until' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'    => 'Draft',
            'sent'     => 'Sent',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            default    => 'Draft',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'    => 'text-muted',
            'sent'     => 'text-brand',
            'accepted' => 'text-pass',
            'rejected' => 'text-danger',
            default    => 'text-muted',
        };
    }
}

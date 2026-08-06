<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class BuyerDeclaration extends Model
{
    use BelongsToCompany;
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

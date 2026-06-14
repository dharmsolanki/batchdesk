<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    protected $casts = [
        'trial_ends_at'       => 'datetime',
        'subscribed'          => 'boolean',
        'subscribed_at'       => 'datetime',
        'subscription_ends_at'=> 'datetime',
        'is_admin'            => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Is trial still active?
    public function isTrialActive(): bool
    {
        return !$this->subscribed
            && $this->trial_ends_at
            && $this->trial_ends_at->isFuture();
    }

    // Is company allowed to use the app?
    public function isActive(): bool
    {
        return $this->subscribed || $this->isTrialActive();
    }

    // Days remaining in trial
    public function trialDaysLeft(): int
    {
        if (!$this->trial_ends_at) return 0;
        return max(0, (int) now()->diffInDays($this->trial_ends_at, false));
    }

    public function statusLabel(): string
    {
        if ($this->subscribed) return 'Subscribed';
        if ($this->isTrialActive()) return 'Trial';
        return 'Expired';
    }

    public function statusColor(): string
    {
        if ($this->subscribed) return 'pass';
        if ($this->isTrialActive()) return 'brand';
        return 'danger';
    }
}

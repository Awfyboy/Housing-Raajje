<?php

namespace App\Models\Entity;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\OwnerFactory;

class Owner extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'national_id',
        'bank_account',
        'verified_phone',
        'address',
    ];

    protected static function newFactory()
    {
        return OwnerFactory::new();
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
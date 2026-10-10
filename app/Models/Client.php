<?php

namespace App\Models\Entity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'date_of_birth',
        'phone',
        'maximum_rent',
        'preferred_location',
        'preferred_rooms',
        'preferred_lease',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'maximum_rent' => 'decimal:2',
            'preferred_rooms' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
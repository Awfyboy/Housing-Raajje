
<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RENTED = 'rented';
    public const STATUS_UNAVAILABLE = 'unavailable';

    protected $fillable = [
        'owner_id',
        'branch_id',
        'title',
        'description',
        'address',
        'location',
        'monthly_rent',
        'bedrooms',
        'bathrooms',
        'property_type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'monthly_rent' => 'decimal:2',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(PropertyRegistration::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}

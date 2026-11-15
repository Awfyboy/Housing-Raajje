<?php

namespace App\Models\Entity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lease extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_SIGNATURE = 'pending_signature';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_TERMINATED = 'terminated';

    protected $fillable = [
        'lease_number',
        'property_id',
        'owner_id',
        'client_id',
        'staff_id',
        'application_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'security_deposit',
        'advance_payment',
        'owner_signed_at',
        'client_signed_at',
        'signed_document_path',
        'status',
        'termination_reason',
        'terminated_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'monthly_rent' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'advance_payment' => 'decimal:2',
            'owner_signed_at' => 'datetime',
            'client_signed_at' => 'datetime',
            'terminated_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
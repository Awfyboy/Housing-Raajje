<?php

namespace App\Models\Entity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchManager extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ON_LEAVE = 'on_leave';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'user_id',
        'employee_number',
        'appointed_at',
        'employment_status',
    ];

    protected function casts(): array
    {
        return [
            'appointed_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

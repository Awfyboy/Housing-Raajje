<?php

namespace App\Models\Entity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchManager extends Model
{
    protected $fillable = [
        'user_id',
        'employee_number',
        'appointed_at',
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

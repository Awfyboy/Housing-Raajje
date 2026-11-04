<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewingFeedback extends Model
{
    protected $fillable = [
        'viewing_id',
        'rating',
        'comments',
        'interested',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'interested' => 'boolean',
        ];
    }

    public function viewing(): BelongsTo
    {
        return $this->belongsTo(Viewing::class);
    }
}
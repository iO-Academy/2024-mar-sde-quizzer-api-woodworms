<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class question extends Model
{
    use HasFactory;

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(answer::class);
    }
}

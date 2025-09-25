<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BurialRight extends Model
{
    /** @use HasFactory<\Database\Factories\BurialRightFactory> */
    use HasFactory;
    protected $fillable = [
        'burial_right_bundle_id',
        'plot_id',
        'notes',
    ];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(BurialRightBundle::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function purchaser()
    {
        return $this->bundle->purchaser;
    }
}

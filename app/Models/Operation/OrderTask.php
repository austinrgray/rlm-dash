<?php

namespace App\Models\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'task_name',
        'status',
        'scheduled_date',
        'sequence',
        'metadata',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'metadata'       => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

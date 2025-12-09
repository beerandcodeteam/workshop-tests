<?php

namespace App\Models;

use App\OrderStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'payment_id',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
            'status' => OrderStatus::class,
        ];
    }

    public function getTotalInReaisAttribute(): string
    {
        return number_format($this->total / 100, 2, ',', '.');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

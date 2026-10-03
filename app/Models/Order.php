<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = ['new', 'packed', 'cancelled'];

    protected $fillable = [
        'token',
        'customer_name',
        'phone',
        'address',
        'status',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->token ??= Str::random(40);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'Новый',
            'packed' => 'Собран',
            'cancelled' => 'Отменён',
            default => $this->status,
        };
    }
}

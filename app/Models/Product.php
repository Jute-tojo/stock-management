<?php

namespace App\Models;

use App\Enums\ProductUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'image',
        'description',
        'price',
        'quantity',
        'unit',
    ];

    protected $appends = ['image_url', 'unit_label'];

    protected function casts(): array
    {
        return [
            'unit' => ProductUnit::class,
            'price' => 'decimal:2',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }

    public function getUnitLabelAttribute(): string
    {
        return $this->unit?->label() ?? (string) $this->unit;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $lastId = static::max('id') ?? 0;
            $product->sku = 'PRO-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}

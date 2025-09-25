<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellWaste extends Model
{
    protected $table = 'sell_waste';

    protected $fillable = [ 
        'user_id',
        'waste_category_id',
        'sell_waste_type_id',
        'weight_kg',
        'price_per_kg',
        'total_price',
        'status',
        'sell_method',
        'description',
        'points_awarded',
        'photo_path',
        'handled_by_staff_id',
        'handled_at'
    ];

    protected $casts = [
        'weight_kg' => 'float',
        'price_per_kg' => 'float',
        'total_price' => 'float',
        'points_awarded' => 'integer',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sellType(): BelongsTo
    {
        return $this->belongsTo(SellWasteType::class, 'sell_waste_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SellWastePhoto::class, 'sell_id');
    }

    // helper: main photo (fallback to photo_path)
    public function getMainPhotoAttribute(): ?string
    {
        if ($this->photos()->exists()) {
            $p = $this->photos()->orderBy('sort_order')->first();
            if ($p) return $p->photo_path;
        }
        return $this->photo_path;
    }
}

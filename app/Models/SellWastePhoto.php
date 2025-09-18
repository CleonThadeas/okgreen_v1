<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellWastePhoto extends Model
{
    protected $table = 'sell_waste_photos';

    protected $fillable = [
        'sell_id',
        'photo_path',
        'sort_order'
    ];

    public function sell(): BelongsTo
    {
        return $this->belongsTo(SellWaste::class, 'sell_id');
    }
}

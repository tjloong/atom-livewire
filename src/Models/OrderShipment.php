<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'order_id' => 'integer',
    ];

    /**
     * Get order for shipment
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(model('order'));
    }
}

<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasTrace;

class DocumentPayment extends Model
{
    use HasFactory;
    use HasTrace;

    protected $guarded = [];

    protected $casts = [
        'currency_rate' => 'float',
        'amount' => 'float',
        'document_id' => 'integer',
        'paid_at' => 'date',
    ];

    /**
     * Model boot
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($payment) {
            $payment->number = $payment->number ?? $payment->generateNumber();
        });
    }

    /**
     * Get document for document payment
     */
    public function document()
    {
        return $this->belongsTo(get_class(model('document')));
    }

    /**
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->paid_at) return 'paid';

        return 'pending';
    }

    /**
     * Generate number
     */
    public function generateNumber()
    {
        $count = 0;
        $dup = true;

        while($dup) {
            $count++;
            $number = collect([
                $this->document->number,
                [
                    'invoice' => 'R',
                    'bill' => 'V',
                ][$this->document->type],
                $count,
            ])->filter()->join('-');

            $dup = $this->document->payments()->where('number', $number)->count() > 0;
        }

        return $number;
    }
}

<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Jiannius\Atom\Traits\Models\HasCurrency;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasShareable;
use Jiannius\Atom\Traits\Models\HasTrace;

class Document extends Model
{
    use HasCurrency;
    use HasFactory;
    use HasFilters;
    use HasShareable;
    use HasTrace;

    protected $guarded = [];

    protected $casts = [
        'subtotal' => 'float',
        'discount_total' => 'float',
        'tax_total' => 'float',
        'paid_total' => 'float',
        'grand_total' => 'float',
        'splitted_total' => 'float',
        'data' => 'object',
        'contact_id' => 'integer',
        'revision_for_id' => 'integer',
        'converted_from_id' => 'integer',
        'splitted_from_id' => 'integer',
        'issued_at' => 'date',
        'due_at' => 'date',
        'last_sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public $types = [
        'quotation',
        'invoice',
        'sales-order',
        'delivery-order',
        'purchase-order',
        'bill',
    ];

    /**
     * The "booted" method for model
     */
    protected static function booted(): void
    {
        static::saving(function($document) {
            $document->number = $document->prefix.$document->postfix.($document->rev ? '.'.$document->rev : '');
            $document->issued_at = $document->issued_at ?? now();

            if (is_numeric($document->payterm)) {
                $document->due_at = $document->issued_at->addDays($document->payterm);
            }
        });
    }

    /**
     * Get contact for document
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(model('contact'));
    }

    /**
     * Get revision for document
     */
    public function revisionFor(): BelongsTo
    {
        return $this->belongsTo(model('document'), 'revison_for_id');
    }

    /**
     * Get converted from for document
     */
    public function convertedFrom(): BelongsTo
    {
        return $this->belongsTo(model('document'), 'converted_from_id');
    }

    /**
     * Get converted to for document
     */
    public function convertedTo(): HasMany
    {
        return $this->hasMany(model('document'), 'converted_from_id');
    }

    /**
     * Get splitted from for document
     */
    public function splittedFrom(): BelongsTo
    {
        return $this->belongsTo(model('document'), 'splitted_from_id');
    }

    /**
     * Get splits for document
     */
    public function splits(): HasMany
    {
        return $this->hasMany(model('document'), 'splitted_from_id');
    }

    /**
     * Get items for document
     */
    public function items(): HasMany
    {
        return $this->hasMany(model('document_item'));
    }

    /**
     * Get files for document
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(model('file'), 'document_files');
    }

    /**
     * Get emails for document
     */
    public function emails(): HasMany
    {
        return $this->hasMany(model('document_email'));
    }

    /**
     * Get payments for document
     */
    public function payments(): HasMany
    {
        return $this->hasMany(model('document_payment'));
    }

    /**
     * Get labels for document
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(model('label'), 'document_labels');
    }
    /**
     * Attribute for is splitted
     */
    protected function isSplitted(): Attribute
    {
        return new Attribute(
            get: fn () => $this->splittedFrom || $this->splits()->count(),
        );
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return new Attribute(
            get: function() {
                switch ($this->type) {
                    case 'invoice':
                        if ($this->paid_total > 0 && $this->paid_total >= $this->splitted_total) return 'paid';
                        elseif ($this->paid_total > 0 && $this->paid_total >= $this->grand_total) return 'paid';
                        elseif ($this->paid_total > 0) return 'partial';
                        elseif ($this->due_at && ($this->due_at->isPast() || $this->due_at->isToday())) return 'due';
                        elseif ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        elseif ($this->last_sent_at) return 'sent';
                        else return 'unpaid';
                        break;

                    case 'quotation':
                        $validfor = data_get($this->data, 'valid_for');

                        if ($this->convertedTo->count()) return 'invoiced';
                        elseif ($this->issued_at && $validfor && $this->issued_at->addDays($validfor)->isPast()) return 'expired';
                        elseif ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        elseif ($this->last_sent_at) return 'sent';
                        else return 'draft';
                        break;
                    
                    case 'sales-order':
                        if ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        elseif ($this->convertedTo->count()) return 'invoiced';
                        else return 'draft';
                        break;

                    case 'delivery-order':
                        if ($this->delivered_at) return 'delivered';
                        elseif ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        else return 'draft';
                        break;
                    
                    case 'purchase-order':
                        if ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        elseif ($this->convertedTo->count()) return 'billed';
                        else return 'draft';
                        break;

                    case 'bill':
                        if ($this->paid_total > 0 && $this->paid_total >= $this->grand_total) return 'paid';
                        elseif ($this->paid_total > 0) return 'partial';
                        elseif ($this->due_at && ($this->due_at->isPast() || $this->due_at->isToday())) return 'due';
                        elseif ($this->issued_at && $this->issued_at->isFuture()) return 'future';
                        else return 'unpaid';
                        break;
    
                    default:
                        return 'draft';
                        break;
                }
            },
        );
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('documents.number', $search)
            ->orWhere('documents.name', 'like', "%$search%")
        );
    }

    /**
     * Get taxes
     */
    public function getTaxes(): mixed
    {
        if (!enabled_module('taxes')) return null;

        $items = $this->splittedFrom
            ? $this->splittedFrom->items
            : $this->items;

        $taxes = $items->map(fn($item) => $item->taxes)->collapse()->map(fn($tax) => [
            'id' => $tax->id, 
            'amount' => $tax->pivot->amount, 
            'label' => $tax->label,
        ]);

        return $taxes->unique('id')->map(fn($tax) => array_merge($tax, [
            'amount' => $taxes->where('id', data_get($tax, 'id'))->sum('amount')
        ]));
    }

    /**
     * Get columns
     */
    public function getColumns(): mixed
    {
        $cols = array_merge(
            [
                'item_name',
                'item_description',
                'item_category',
                'qty',
            ],
            $this->type === 'delivery-order'
                ? []
                : [
                    'price',
                    'total',
                    'tax',
                ]
        );

        return collect($cols)->mapWithKeys(fn($value) => [
            $value => str($value)->headline()->toString(),
        ])->filter();
    }

    /**
     * Set summary
     */
    public function setSummary(): void
    {
        $this->fill([
            'summary' => str()->limit($this->items->pluck('name')->join(', '), 200)
        ])->saveQuietly();
    }

    /**
     * Set prefix and postfix
     */
    public function setPrefixAndPostfix(): void
    {
        $pattern = enabled_module('tenants') 
            ? tenant('settings.'.$this->type.'.prefix')
            : data_get($this->numberPrefix, $this->type);

        $prefix = str($pattern)
            ->replace('%y', date('y'))
            ->replace('%m', date('m'))
            ->replace('%d', date('d'))
            ->toString();

        $query = model('document')
            ->readable()
            ->where('type', $this->type)
            ->whereNull('rev');

        $dup = true;
        $postfix = null;
        $latest = (clone $query)->latest('id')->first();

        while ($dup) {
            if ($latest) {
                if (is_numeric($latest->postfix)) $postfix = $latest->postfix + 1;
                else $postfix = str()->random(6);
            }
            else $postfix = 1;

            $postfix = str()->padLeft($postfix, 6, '0');
            $dup = (clone $query)->where('prefix', $prefix)->where('postfix', $postfix)->count() > 0;
        }

        $this->fill(compact('prefix', 'postfix'));
    }

    /**
     * Sum total
     */
    public function sumTotal(): void
    {
        $subtotal = $this->items()->sum('subtotal');
        $taxTotal = enabled_module('taxes') ? $this->getTaxes()->sum('amount') : 0;
        $paidTotal = $this->payments()->sum('amount');

        $this->fill([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'paid_total' => $paidTotal,
            'grand_total' => $subtotal + $taxTotal,
        ])->saveQuietly();
    }

    /**
     * Sync splits
     */
    public function syncSplits(): void
    {
        if (!$this->splits->count()) return;

        foreach ($this->splits as $split) {
            $split->fill([
                'prefix' => $this->prefix,
                'postfix' => $this->postfix,
                'name' => $this->name,
                'address' => $this->address,
                'person' => $this->person,
                'reference' => $this->reference,
                'description' => $this->description,
                'summary' => $this->summary,
                'currency' => $this->currency,
                'currency_rate' => $this->currency_rate,
                'subtotal' => $this->subtotal,
                'discount_total' => $this->discount_total,
                'tax_total' => $this->tax_total,
                'grand_total' => $this->grand_total,
                'splitted_total' => $this->grand_total * (($split->data->percentage ?? 0)/100),
                'footer' => $this->footer,
                'note' => $this->note,
                'contact_id' => $this->contact_id,
                'shareable_id' => $this->shareable_id,
                'revision_for_id' => $this->revision_for_id,
                'converted_from_id' => $this->converted_from_id,
            ])->saveQuietly();

            $this->fill([
                'splitted_total' => $this->grand_total * (($this->data->percentage ?? 0)/100),
            ])->saveQuietly();
        }
    }

    /**
     * Pdf
     */
    public function pdf($raw = false)
    {
        $filename = $this->number.'.pdf';
        $path = storage_path($filename);
        $view = view()->exists('pdf.document') ? 'pdf.document' : 'atom::pdf.document';
        $pdf = pdf($view, [
            'document' => $this,
            'filename' => $filename,
        ]);
        
        if ($raw) return $pdf;
        else {
            $pdf->save($path);
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }
}

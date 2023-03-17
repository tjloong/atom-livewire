<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\DocumentEmailNotification;
use Jiannius\Atom\Traits\Models\HasTrace;

class DocumentEmail extends Model
{
    use HasTrace;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'from' => 'object',
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'document_id' => 'integer',
    ];

    /**
     * Get document for document email
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(model('document'));
    }

    /**
     * Notify
     */
    public function notify(): void
    {
        Notification::route('mail', $this->to)->notify(new DocumentEmailNotification($this));
    }
}

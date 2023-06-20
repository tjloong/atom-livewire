<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;

class Email extends Model
{
    use HasTrace;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'from' => 'object',
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'data' => 'array',
    ];

    /**
     * Notify
     */
    public function notify(): void
    {
        $notification = collect([
            'App\Notifications\Email\SendNotification',
            'Jiannius\Atom\Notifications\Email\SendNotification',
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        Notification::route('mail', $this->to)
            ->notify(new $notification($this));
    }
}

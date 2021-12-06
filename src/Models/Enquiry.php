<?php

namespace Jiannius\Atom\Models;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\EnquiryNotification;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
    ];

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
        );
    }

    /**
     * Notify owner about the enquiry
     * 
     * @return void
     */
    public function notify()
    {
        $settings = SiteSetting::email()->get();
        $to = $settings->where('name', 'notify_to')->first()->value;

        if ($to) Notification::route('mail', $to)->notify(new EnquiryNotification($this));
    }
}

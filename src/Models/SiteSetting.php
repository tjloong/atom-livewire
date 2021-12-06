<?php

namespace Jiannius\Atom\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];

    public $timestamps = false;

    /**
     * Scope for seo
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeSeo($query)
    {
        return $query->whereIn('name', ['seo_title', 'seo_description', 'seo_image']);
    }

    /**
     * Scope for tracking
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeTracking($query)
    {
        return $query->whereIn('name', ['ga_id', 'gtm_id', 'fbpixel_id']);
    }

    /**
     * Scope for email
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeEmail($query)
    {
        return $query->whereIn('name', ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'notify_from', 'notify_to']);
    }

    /**
     * Configure SMTP
     * 
     * @return void
     */
    public static function configureSMTP()
    {
        if (Schema::hasTable((new self())->getTable())) {
            $settings = self::email()->get();
    
            config([
                'mail.mailers.smtp.host' => $settings->where('name', 'smtp_host')->first()->value,
                'mail.mailers.smtp.port' => $settings->where('name', 'smtp_port')->first()->value,
                'mail.mailers.smtp.username' => $settings->where('name', 'smtp_username')->first()->value,
                'mail.mailers.smtp.password' => $settings->where('name', 'smtp_password')->first()->value,
                'mail.mailers.smtp.encryption' => $settings->where('name', 'smtp_encryption')->first()->value,
                'mail.from.address' => $settings->where('name', 'notify_from')->first()->value,
                'mail.from.name' => config('app.name'),
            ]);
        }
    }
}

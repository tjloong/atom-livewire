<?php

namespace Jiannius\Atom\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];

    public $timestamps = false;

    /**
     * Scope for contact
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeContact($query)
    {
        return $query->whereIn('name', [
            'company', 
            'phone', 
            'email',
            'whatsapp',
            'address',
        ]);
    }

    /**
     * Scope for social media
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeSocial($query)
    {
        return $query->whereIn('name', [
            'facebook',
            'twitter',
            'linkedin',
            'instagram',
            'youtube',
            'spotify',
            'tiktok',
        ]);
    }

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
        return $query->whereIn('name', [
            'mailer',
            'smtp_host', 
            'smtp_port', 
            'smtp_username', 
            'smtp_password', 
            'smtp_encryption', 
            'mailgun_domain',
            'mailgun_secret',
            'notify_from', 
            'notify_to',
        ]);
    }

    /**
     * Scope for storage
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeStorage($query)
    {
        return $query->whereIn('name', [
            'filesystem',
            'do_spaces_key',
            'do_spaces_secret',
            'do_spaces_region',
            'do_spaces_bucket',
            'do_spaces_endpoint',
            'do_spaces_cdn',
        ]);
    }

    /**
     * Get settings
     * 
     * @param string $name
     * @param Collection $collection
     * @return mixed
     */
    public static function getSetting($name, $collection = null)
    {
        $setting = $collection
            ? $collection->where('name', $name)->first()
            : self::where('name', $name)->first();

        return optional($setting)->value;
    }

    /**
     * Configure SMTP
     * 
     * @return void
     */
    public static function configureSMTP()
    {
        try {
            $settings = self::email()->get();
            $mailer = self::getSetting('mailer', $settings);

            if ($mailer === 'smtp') {
                config([
                    'mail.mailers.smtp.host' => self::getSetting('smtp_host', $settings),
                    'mail.mailers.smtp.port' => self::getSetting('smtp_port', $settings),
                    'mail.mailers.smtp.username' => self::getSetting('smtp_username', $settings),
                    'mail.mailers.smtp.password' => self::getSetting('smtp_password', $settings),
                    'mail.mailers.smtp.encryption' => self::getSetting('smtp_encryption', $settings),
                ]);
            }
            else if ($mailer === 'mailgun') {
                config([
                    'services.mailgun.domain' => self::getSetting('mailgun_domain', $settings),
                    'services.mailgun.secret' => self::getSetting('mailgun_secret', $settings),
                ]);
            }
    
            config([
                'mail.default' => $mailer,
                'mail.from.address' => self::getSetting('notify_from', $settings),
                'mail.from.name' => config('app.name'),
            ]);
        } catch (\Throwable $th) {
            logger('Unable to configure SMTP from site settings.');
            logger($th->getMessage());
        }
    }

    /**
     * Get digital ocean disk
     * 
     * @return Storage
     */
    public static function getDoDisk()
    {
        $settings = self::storage()->get();
        $key = self::getSetting('do_spaces_key', $settings);
        $secret = self::getSetting('do_spaces_secret', $settings);

        if ($key && $secret) {
            config([
                'filesystems.disks.do' => [
                    'driver' => 's3',
                    'key' => $key,
                    'secret' => $secret,
                    'region' => self::getSetting('do_spaces_region', $settings),
                    'bucket' => self::getSetting('do_spaces_bucket', $settings),
                    'endpoint' => self::getSetting('do_spaces_endpoint', $settings),
                ],
            ]);
    
            return Storage::disk('do');
        }

        return false;
    }
}

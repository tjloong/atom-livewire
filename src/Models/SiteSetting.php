<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $groups = [
        'profile' => [
            'company', 
            'phone', 
            'email',
            'address',
            'gmap_url',
            'briefs',    
        ],
        'social' => [
            'facebook',
            'twitter',
            'linkedin',
            'instagram',
            'youtube',
            'spotify',
            'tiktok',
        ],
        'seo' => [
            'seo_title', 
            'seo_description', 
            'seo_image',
        ],
        'analytics' => [
            'ga_id', 
            'gtm_id', 
            'fbpixel_id',
        ],
        'email' => [
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
        ],
        'whatsapp' => [
            'whatsapp',
            'whatsapp_bubble',
            'whatsapp_text',    
        ],
        'do' => [
            'do_spaces_key',
            'do_spaces_secret',
            'do_spaces_region',
            'do_spaces_bucket',
            'do_spaces_endpoint',
            'do_spaces_folder',    
        ],
        'stripe' => [
            'stripe_public_key', 
            'stripe_secret_key', 
            'stripe_webhook_signing_secret',
        ],
        'gkash' => [
            'gkash_mid',
            'gkash_signature_key',
            'gkash_url',
            'gkash_sandbox_url',
        ],
        'ozopay' => [
            'ozopay_tid',
            'ozopay_secret',
            'ozopay_url',
            'ozopay_sandbox_url',
        ],
        'ipay' => [
            'ipay_merchant_code',
            'ipay_merchant_key',
            'ipay_url',
            'ipay_query_url',
        ],
    ];

    /**
     * Scope for group
     */
    public function scopeGroup($query, $group)
    {
        return $query->whereIn('name', $this->groups[$group]);
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
     * Set settings
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setSetting($key, $value)
    {
        self::where('name', $key)->update(['value' => $value]);
    }

    /**
     * Configure SMTP
     * 
     * @return void
     */
    public static function configureSMTP()
    {
        if (config('atom.static_site')) return;

        try {
            $mailer = site_settings('mailer');

            if ($mailer === 'smtp') {
                config([
                    'mail.mailers.smtp.host' => site_settings('smtp_host'),
                    'mail.mailers.smtp.port' => site_settings('smtp_port'),
                    'mail.mailers.smtp.username' => site_settings('smtp_username'),
                    'mail.mailers.smtp.password' => site_settings('smtp_password'),
                    'mail.mailers.smtp.encryption' => site_settings('smtp_encryption'),
                ]);
            }
            else if ($mailer === 'mailgun') {
                config([
                    'services.mailgun.domain' => site_settings('mailgun_domain'),
                    'services.mailgun.secret' => site_settings('mailgun_secret'),
                ]);
            }
    
            config([
                'mail.default' => $mailer,
                'mail.from.address' => site_settings('notify_from'),
                'mail.from.name' => config('app.name'),
            ]);
        } catch (\Throwable $th) {
            logger('Unable to configure SMTP from site settings.');
            logger($th->getMessage());
        }
    }

    /**
     * Get contact info
     */
    public function getContactInfo()
    {
        $waNum = str()->replace('+', '', site_settings('whatsapp'));
        $waText = site_settings('whatsapp_text');
        
        $waUrl = 'https://wa.me/'.$waNum;
        if (!empty($waText)) $waUrl .= '?text='.urlencode($waText);

        return (object)[
            'company' => site_settings('company'),
            'phone' => site_settings('phone'),
            'email' => site_settings('email'),
            'address' => site_settings('address'),
            'briefs' => site_settings('briefs'),

            'socials' => model('site_setting')->group('social')->get()
                    ->mapWithKeys(fn($val) => [$val->name => $val->value])
                    ->filter()
                    ->all(),
            
            'whatsapp' => [
                'number' => $waNum,
                'bubble' => (bool)site_settings('whatsapp_bubble'),
                'text' => $waText,
                'url' => $waUrl,
            ],
        ];
    }
}

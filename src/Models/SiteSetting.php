<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * Model booted
     */
    protected static function booted()
    {
        static::saving(function($setting) {
            cache()->forget('settings');
        });
    }

    /**
     * Scope for group
     */
    public function scopeGroup($query, $group)
    {
        return $query->whereIn('name', $this->groups[$group]);
    }

    /**
     * Generate settings array
     */
    public function generate()
    {
        return cache()->remember('settings', now()->addDays(7), function() {
            return $this->get()
                ->mapWithKeys(fn($val) => [
                    $val->name => in_array(data_get($val, 'name'), ['modules', 'announcements', 'popup', 'analytics'])
                        ? json_decode($val->value, true)
                        : $val->value,
                ])
                ->toArray();
        });
    }

    /**
     * Repair
     */
    public function repair()
    {
        $defaults = json_decode(file_get_contents(atom_path('resources/json/site-settings.json')), true);
        $settings = settings();
        $inserts = collect();

        foreach ($defaults as $name => $value) {
            if ($name === 'analytics') {
                $existingData = [
                    'fathom_id' => data_get($settings, 'fathom_id'),
                    'ga_id' => data_get($settings, 'ga_id'),
                    'gtm_id' => data_get($settings, 'gtm_id'),
                    'fbp_id' => data_get($settings, 'fbpixel_id'),
                ];
            }
            else $existingData = data_get($settings, $name);

            $value = $existingData ?? $value;

            $inserts->push([
                'name' => $name,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]);
        }

        DB::table($this->getTable())->truncate();
        DB::table($this->getTable())->insert($inserts->toArray());
    }

    /**
     * Get contact info
     */
    public function getContactInfo()
    {
        $waNum = str()->replace('+', '', settings('whatsapp'));
        $waText = settings('whatsapp_text');
        
        $waUrl = 'https://wa.me/'.$waNum;
        if (!empty($waText)) $waUrl .= '?text='.urlencode($waText);

        return (object)[
            'company' => settings('company'),
            'phone' => settings('phone'),
            'email' => settings('email'),
            'address' => settings('address'),
            'briefs' => settings('briefs'),

            'socials' => model('site_setting')->group('social')->get()
                    ->mapWithKeys(fn($val) => [$val->name => $val->value])
                    ->filter()
                    ->all(),
            
            'whatsapp' => [
                'number' => $waNum,
                'bubble' => (bool)settings('whatsapp_bubble'),
                'text' => $waText,
                'url' => $waUrl,
            ],
        ];
    }
}

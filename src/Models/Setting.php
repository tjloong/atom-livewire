<?php

namespace Jiannius\Atom\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    // booted
    protected static function booted()
    {
        static::saving(function($setting) {
            cache()->forget('settings');
        });
    }

    // scope for group
    public function scopeGroup($query, $group): void
    {
        $query->where('name', 'like', $group.'_%');
    }

    // generate settings array
    public function generate()
    {
        return cache()->remember('settings', now()->addDays(7), function() {
            return $this->get()->mapWithKeys(function($setting) {
                $cast = get($this->castSettingsValue(), $setting->name);
                $value = $setting->value;
    
                if ($cast === 'boolean') $value = (bool) $value;
                if ($cast === 'float') $value = (float) $value;
                if ($cast === 'array') $value = json_decode($value, true);
                if ($cast === 'json') $value = json_decode($value);
                if ($cast === 'date') $value = new Carbon($value);
    
                return [
                    $setting->name => $value,
                ];
            })->toArray();
        });
    }

    // cast settings value
    public function castSettingsValue() : array
    {
        return [
            'whatsapp_bubble' => 'boolean',
            'revenue_monster_is_sandbox' => 'boolean',
        ];
    }

    // reset
    public function reset()
    {
        $path = collect([
            base_path('resources/json/settings.json'),
            atom_path('resources/json/settings.json'),
        ])->filter(fn($val) => file_exists($val))->first();

        $defaults = json_decode(file_get_contents($path), true);
        $inserts = collect();

        foreach ($defaults as $key => $value) {
            $current = [
                'popup_content' => settings('popup.content'),
                'popup_delay' => settings('popup.delay'),
                'fbpixel_id' => settings('facebook_pixel_id') ?? settings('analytics.fbp_id') ?? settings('fbpixel_id'),
                'ga_id' => settings('google_analytics_id') ?? settings('analytics.ga_id') ?? settings('ga_id'),
                'gtm_id' => settings('google_tag_manager_id') ?? settings('analytics.gtm_id') ?? settings('gtm_id'),
                'site_name' => settings('company'),
                'site_description' => settings('briefs'),
                'contact_name' => settings('company'),
                'contact_phone' => settings('phone'),
                'contact_email' => settings('email'),
                'contact_address' => settings('address'),
                'contact_map' => settings('gmap_url'),
                'facebook_url' => settings('facebook'),
                'instagram_url' => settings('instagram'),
                'twitter_url' => settings('twitter'),
                'linkedin_url' => settings('linkedin'),
                'youtube_url' => settings('youtube'),
                'spotify_url' => settings('spotify'),
                'tiktok_url' => settings('tiktok'),
                'meta_title' => settings('seo_title'),
                'meta_description' => settings('seo_description'),
                'meta_image' => settings('seo_image'),
                'site_whatsapp_number' => settings('whatsapp'),
            ][$key] ?? settings($key);

            $value = (empty($current) || (
                !app()->environment('production')
                && in_array($key, ['mailer', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption'])
            )) ? $value : $current;

            $inserts->push([
                'name' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]);
        }

        DB::table($this->getTable())->truncate();
        DB::table($this->getTable())->insert($inserts->toArray());
    }
}

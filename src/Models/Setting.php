<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $jsons = [
        'whatsapp_bubble',
        'revenue_monster_is_sandbox',
    ];

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
            return $this->get()
                ->mapWithKeys(fn($val) => [
                    $val->name => in_array(data_get($val, 'name'), $this->jsons)
                        ? json_decode($val->value, true)
                        : $val->value,
                ])
                ->toArray();
        });
    }

    // repair
    public function repair()
    {
        $defaults = json_decode(file_get_contents(atom_path('resources/json/settings.json')), true);
        $settings = settings();
        $inserts = collect();

        foreach ($defaults as $name => $value) {
            if ($name === 'popup_content') $existingData = data_get($settings, 'popup.content');
            else if ($name === 'popup_delay') $existingData = data_get($settings, 'popup.delay');
            else if ($name === 'fbpixel_id') $existingData = data_get($settings, 'facebook_pixel_id') ?? data_get($settings, 'analytics.fbp_id') ?? data_get($settings, 'fbpixel_id');
            else if ($name === 'ga_id') $existingData = data_get($settings, 'google_analytics_id') ?? data_get($settings, 'analytics.ga_id') ?? data_get($settings, 'ga_id');
            else if ($name === 'gtm_id') $existingData = data_get($settings, 'google_tag_manager_id') ?? data_get($settings, 'analytics.gtm_id') ?? data_get($settings, 'gtm_id');
            else if ($name === 'site_name') $existingData = data_get($settings, 'company');
            else if ($name === 'site_description') $existingData = data_get($settings, 'briefs');
            else if ($name === 'contact_name') $existingData = data_get($settings, 'company');
            else if ($name === 'contact_phone') $existingData = data_get($settings, 'phone');
            else if ($name === 'contact_email') $existingData = data_get($settings, 'email');
            else if ($name === 'contact_address') $existingData = data_get($settings, 'address');
            else if ($name === 'contact_map') $existingData = data_get($settings, 'gmap_url');
            else if ($name === 'facebook_url') $existingData = data_get($settings, 'facebook');
            else if ($name === 'instagram_url') $existingData = data_get($settings, 'instagram');
            else if ($name === 'twitter_url') $existingData = data_get($settings, 'twitter');
            else if ($name === 'linkedin_url') $existingData = data_get($settings, 'linkedin');
            else if ($name === 'youtube_url') $existingData = data_get($settings, 'youtube');
            else if ($name === 'spotify_url') $existingData = data_get($settings, 'spotify');
            else if ($name === 'tiktok_url') $existingData = data_get($settings, 'tiktok');
            else if ($name === 'meta_title') $existingData = data_get($settings, 'seo_title');
            else if ($name === 'meta_description') $existingData = data_get($settings, 'seo_description');
            else if ($name === 'meta_image') $existingData = data_get($settings, 'seo_image');
            else if ($name === 'site_whatsapp_number') $existingData = data_get($settings, 'whatsapp');
            else $existingData = data_get($settings, $name);

            $value = empty($existingData) ? $value : $existingData;

            $inserts->push([
                'name' => $name,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]);
        }

        DB::table($this->getTable())->truncate();
        DB::table($this->getTable())->insert($inserts->toArray());
    }
}

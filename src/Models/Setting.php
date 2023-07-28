<?php

namespace Jiannius\Atom\Models;

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
            return $this->get()
                ->mapWithKeys(fn($val) => [
                    $val->name => in_array(data_get($val, 'name'), ['modules', 'announcements', 'popup', 'analytics'])
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
            else if ($name === 'facebook_pixel_id') $existingData = data_get($settings, 'analytics.fbp_id') ?? data_get($settings, 'fbpixel_id');
            else if ($name === 'fathom_analytics_id') $existingData = data_get($settings, 'analytics.fathom_id') ?? data_get($settings, 'fathom_id');
            else if ($name === 'google_analytics_id') $existingData = data_get($settings, 'analytics.ga_id') ?? data_get($settings, 'ga_id');
            else if ($name === 'google_tag_manager_id') $existingData = data_get($settings, 'analytics.gtm_id') ?? data_get($settings, 'gtm_id');
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

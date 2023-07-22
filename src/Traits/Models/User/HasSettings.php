<?php

namespace Jiannius\Atom\Traits\Models\User;

trait HasSettings
{
    // boot has settings
    protected static function bootHasSettings(): void
    {
        static::created(function($user) {
            model('user_setting')->initialize($user->id);
        });
    }

    // settings
    public function settings($key = null, $default = null)
    {
        $settings = cache()->remember('user_settings', now()->addDays(7), function() {
            return model('user_setting')
                ->where('user_id', $this->id)
                ->get()
                ->mapWithKeys(fn($setting) => [$setting->name => $setting->value])
                ->toArray();
        });

        if (!$key) return $settings;
        else if (is_string($key)) return data_get($settings, $key, $default);
        else if (is_array($key)) {
            foreach ($key as $name => $val) {
                model('user_setting')->updateOrCreate(
                    [
                        'name' => $name,
                        'user_id' => $this->id,
                    ],
                    ['value' => $val],
                );
            }
        }
    }    
}
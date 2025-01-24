<?php

namespace Jiannius\Atom\Traits\Models;

// should be consumed by any model with settings column
trait Settings
{
    protected function initializeSettings() : void
    {
        $this->casts['settings'] = 'array';
    }

    public function resetSettings() : void
    {
        $this->fill([
            'settings' => $this->getDefaultSettings(),
        ])->saveQuietly();
    }

    public function getDefaultSettings() : array
    {
        $model = str($this->getTable())->singular()->toString();
        $filename = $model.'-settings.json';
        $path = collect([
            base_path('resources/json/'.$filename),
            atom_path('resources/json/'.$filename),
        ])->first(fn($path) => file_exists($path));

        return json_decode(file_get_contents($path), true);
    }

    // $model->settings('name') to get setting value
    // $model->settings(['name' => 'value']) to save settings
    public function settings($name = null, $default = null) : mixed
    {
        $settings = $this->settings ?? [];

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $settings[$key] = $value;
            }

            $this->fill(compact('settings'))->save();

            return $settings;
        }
        else if ($name) {
            if (method_exists($this, 'castSettings')) {
                $casts = $this->castSettings();
                if (isset($casts[$name])) return $casts[$name];
            }

            $value = get($this->settings, $name);

            return $value;
        }
        else {
            return $settings;
        }
    }
}
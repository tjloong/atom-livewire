<?php

namespace Jiannius\Atom\Traits\Models;

// should be consumed by any model with settings column
trait Settings
{
    // initialize
    protected function initializeSettings() : void
    {
        $this->casts['settings'] = 'array';
    }

    // reset settings
    public function resetSettings() : void
    {
        $this->fill(['settings' => $this->getDefaultSettings()])->saveQuietly();
    }

    // get default settings
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

    // settings helper
    public function settings($name = null, $default = null) : mixed
    {
        if (!$name) return $this->settings;

        if (is_array($name)) {
            $settings = $this->settings ?? [];

            foreach ($name as $key => $value) {
                $settings[$key] = $value;
            }

            $this->fill(compact('settings'))->saveQuietly();

            return $settings;
        }
        else return data_get($this->settings, $name);
    }
}
<?php

namespace Jiannius\Atom\Enums\File;

enum Type
{
    case IMAGE;
    case VIDEO;
    case AUDIO;
    case FILE;
    case YOUTUBE;

    public function mime()
    {
        return match($this) {
            static::IMAGE => 'image/*',
            static::VIDEO => 'video/*',
            static::AUDIO => 'audio/*',
            static::FILE => 'file',
            static::YOUTUBE => 'youtube',
        };
    }

    public function label()
    {
        return str()->title($this->name);
    }

    public function option()
    {
        return ['value' => $this->mime(), 'label' => $this->label()];
    }
}
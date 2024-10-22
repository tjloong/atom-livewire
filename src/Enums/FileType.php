<?php

namespace Jiannius\Atom\Enums;

use Jiannius\Atom\Traits\Enum;

enum FileType : string
{
    use Enum;

    case IMAGE = 'image';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case FILE = 'file';
    case YOUTUBE = 'youtube';

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

    public function option()
    {
        return ['value' => $this->mime(), 'label' => $this->label()];
    }
}
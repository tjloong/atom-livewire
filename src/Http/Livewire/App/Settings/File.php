<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class File extends Component
{
    // get storage used property
    public function getStorageUsedProperty() : string
    {
        $sum = model('file')->sum('size');
        return format_filesize($sum, 'KB');
    }
}
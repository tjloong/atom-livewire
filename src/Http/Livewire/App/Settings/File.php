<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class File extends Component
{
    // get storage used property
    public function getStorageProperty() : string
    {
        return format(
            model('file')->sum('size')
        )->filesize('KB');
    }
}
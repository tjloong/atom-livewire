<?php

namespace App\Models;

use App\Models\File;
use Jiannius\Atom\Models\Banner as AtomBanner;

class Banner extends AtomBanner
{
    /**
     * Get banner image
     */
    public function image()
    {
        return $this->belongsTo(File::class, 'image_id');
    }
}
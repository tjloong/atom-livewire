<?php

namespace App\Models;

use Jiannius\Atom\Models\Label as AtomLabel;

class Label extends AtomLabel
{
    /**
     * Get blogs for label
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blogs_labels', 'label_id', 'blog_id');
    }

    /**
     * Get types
     * 
     * @return array
     */
    public static function getTypes()
    {
        return [
            'blog-category' => 'Blog Categories',
        ];
    }
}
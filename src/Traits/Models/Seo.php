<?php

namespace Jiannius\Atom\Traits\Models;

trait Seo
{
    // initialize
    protected function initializeSeo() : void
    {
        $this->casts['seo'] = 'array';
    }

    // get seo
    public function getSeo() : array
    {
        return [
            'title' => get($this->seo, 'title') ?? $this->name,
            'description' => get($this->seo, 'description') 
                ?? str($this->description ?? strip_tags($this->content))->limit(255)->toString(),
            'image' => get($this->seo, 'image') ?? optional($this->cover)->url,
            'canonical' => route('web.blog', $this->slug),
        ];
    }
}
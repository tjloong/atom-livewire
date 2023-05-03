<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithSeo
{
    public $seo = [
        'title' => null,
        'description' => null,
        'image' => null,
    ];

    /**
     * Set Seo
     */
    public function setSeo($seo)
    {
        $this->seo = [
            'title' => data_get($seo, 'title'),
            'description' => data_get($seo, 'description'),
            'image' => data_get($seo, 'image'),
        ];
    }
}
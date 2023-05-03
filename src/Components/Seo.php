<?php

namespace Jiannius\Atom\Components;

use Jiannius\Atom\Models\SiteSetting;
use Illuminate\View\Component;

class Seo extends Component
{
    public $noindex;
    public $title;
    public $description;
    public $image;
    public $hreflang;
    public $canonical;
    public $jsonld;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct($noindex = false)
    {
        $this->noindex = $noindex;

        if ($noindex) {
            $this->title = config('app.name');
        }
        else {
            if (!config('atom.static_site')) {
                $this->title = settings('seo_title');
                $this->description = settings('seo_description');
                $this->image = settings('seo_image');
                $this->jsonld = [
                    '@context' => 'http://schema.org',
                    '@type' => 'Website',
                    'url' => url()->current(),
                    'name' => $this->title,
                ];
            }
    
            if (config('atom.seo.title')) $this->title = config('atom.seo.title');
            if (config('atom.seo.description')) $this->description = config('atom.seo.description');
            if (config('atom.seo.image')) $this->image = config('atom.seo.image');
            if (config('atom.seo.hreflang')) $this->hreflang = config('atom.seo.hreflang');
            if (config('atom.seo.canonical')) $this->canonical = config('atom.seo.canonical');
            if (config('atom.seo.jsonld')) $this->jsonld = config('atom.seo.jsonld');
    
            if (!$this->title) $this->title = config('app.name');
        }
        
        if (!app()->environment('production')) $this->title = '[' . app()->environment() . '] ' . $this->title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.seo');
    }
}
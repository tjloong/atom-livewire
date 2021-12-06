<?php

namespace Jiannius\Atom\Components;

use App\Models\SiteSetting;
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
        $settings = SiteSetting::seo()->get();

        $this->noindex = $noindex;

        if ($noindex) {
            $this->title = config('app.name');
        }
        else {
            $this->title = $settings->where('name', 'seo_title')->first()->value;
            $this->description = $settings->where('name', 'seo_description')->first()->value;
            $this->image = $settings->where('name', 'seo_image')->first()->value;
            $this->jsonld = [
                '@context' => 'http://schema.org',
                '@type' => 'Website',
                'url' => url()->current(),
                'name' => $this->title,
            ];
    
            if (config('seo.title')) $this->title = config('seo.title');
            if (config('seo.description')) $this->description = config('seo.description');
            if (config('seo.image')) $this->image = config('seo.image');
            if (config('seo.hreflang')) $this->hreflang = config('seo.hreflang');
            if (config('seo.canonical')) $this->canonical = config('seo.canonical');
            if (config('seo.jsonld')) $this->jsonld = config('seo.jsonld');
    
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
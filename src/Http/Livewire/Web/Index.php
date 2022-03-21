<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class Index extends Component
{
    public $page;
    public $livewire;

    /**
     * Mount
     */
    public function mount($slug = null)
    {
        $path = $this->setLocale($slug) ?? 'index';

        if ($this->isBlog($path)) {
            $this->livewire = livewire_name('web/pages/blog');
        }
        else {
            $this->livewire = livewire_name('web/pages/'.$path);
            $this->page = !$this->livewire && enabled_module('pages')
                ? model('page')->where('slug', $path)->first()
                : null;

            if (!$this->page && !$this->livewire) abort(404);
        }
    }

    /**
     * Set locale
     */
    public function setLocale($slug = null)
    {
        if (!$slug) return;

        $locale = collect(config('atom.locales'))->first(fn($val) => head(explode('/', $slug)) === $val);

        if ($locale) {
            app()->setLocale($locale);
            $slug = str($slug)->replaceFirst($locale, '')->replaceFirst('/', '')->__toString();
        }
        
        return empty($slug) ? null : $slug;
    }

    /**
     * Check path is blog
     */
    public function isBlog($path)
    {
        return collect([
            'blog', 'blog/', 'blog/*',
            'blogs', 'blogs/', 'blogs/*',
        ])->filter(fn($val) => str($path)->is($val))->count() > 0 && enabled_module('blogs');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.index')->layout('layouts.web');
    }
}
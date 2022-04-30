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
        $path = $this->getPath($slug);

        if ($this->isBlog($path)) {
            $this->livewire = livewire_name('web/pages/blog');
        }
        else {
            $this->livewire = livewire_name($path ? 'web/pages/'.$path : 'web/pages');
            $this->page = !$this->livewire && enabled_module('pages')
                ? model('page')->where('slug', $path)->first()
                : null;

            if (!$this->page && !$this->livewire) abort(404);
        }
    }

    /**
     * Get path
     */
    public function getPath($slug)
    {
        $path = $slug;

        foreach (config('atom.locales') as $locale) {
            if (str($path)->is($locale)) $path = str()->replaceFirst($locale, '', $path);
            else if (str($path)->is($locale.'/*')) $path = str()->replaceFirst($locale.'/', '', $path);
        }

        return $path;
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
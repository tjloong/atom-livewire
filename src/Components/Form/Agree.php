<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Agree extends Component
{
    public $type;
    public $links;

    /**
     * Contructor
     */
    public function __construct(
        $links = [],
        $tnc = false,
        $marketing = false
    ) {
        $this->type = $tnc 
            ? 'tnc' 
            : ($marketing ? 'marketing' : null);

        $this->links = $links;

        if (!$this->links && $this->type === 'tnc') {
            $this->links = model('page')->whereIn('name', ['Terms', 'Privacy'])
                ->get()
                ->mapWithKeys(fn($page) => [$page->title => '/'.$page->slug])
                ->all();
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.agree');
    }
}
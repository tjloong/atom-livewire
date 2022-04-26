<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class Agree extends Component
{
    public $type;
    public $links;

    /**
     * Contructor
     * 
     * @return void
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
                ->mapWithKeys(fn($page) => [$page->title => route('page', [$page->slug])])
                ->all();
        }
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.input.agree');
    }
}
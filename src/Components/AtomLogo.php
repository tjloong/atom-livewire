<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class AtomLogo extends Component
{
    public $logo;
    public $small;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $small = false,
        $logo = null
    ) {
        $this->small = $small;

        foreach (['png', 'jpg', 'jpeg', 'svg'] as $ext) {
            $name = $logo ?? 'logo';
            $name = ($small ? ($name.'-sm') : $name).'.'.$ext;

            if (file_exists(storage_path("app/public/img/$name"))) {
                $this->logo = $name;
                break;
            }
        }
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.atom-logo');
    }
}
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
    public function __construct($small = false)
    {
        $this->small = $small;

        foreach (['png', 'jpg', 'jpeg', 'svg'] as $ext) {
            if ($small) {
                if (file_exists(storage_path("app/public/img/logo-sm.$ext"))) {
                    $this->logo = "logo-sm.$ext";
                    break;
                }
            }
            else if (file_exists(storage_path("app/public/img/logo.$ext"))) {
                $this->logo = "logo.$ext";
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
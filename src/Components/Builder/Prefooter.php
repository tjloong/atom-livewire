<?php

namespace Jiannius\Atom\Components\Builder;

use Jiannius\Atom\Models\SiteSetting;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Prefooter extends Component
{
    public $cols;
    public $dark;
    public $links;
    public $email;
    public $phone;
    public $address;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $links = [],
        $contact = [],
        $siteSettings = true
    ) {
        if (config('atom.static_site')) {
            $this->phone = $contact['phone'] ?? null;
            $this->email = $contact['email'] ?? null;
            $this->address = $contact['address'] ?? null;
        }
        else if ($siteSettings) {
            $this->phone = isset($contact['phone']) ? $contact['phone'] : SiteSetting::getSetting('phone');
            $this->email = isset($contact['email']) ? $contact['email'] : SiteSetting::getSetting('email');
            $this->address = isset($contact['address']) ? $contact['address'] : SiteSetting::getSetting('address');
        }

        $this->dark = $dark;
        $this->links = $links;
        $this->cols = count(array_keys($this->links)) + 1;
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.prefooter');
    }
}
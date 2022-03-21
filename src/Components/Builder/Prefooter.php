<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;
use Jiannius\Atom\Models\SiteSetting;

class Prefooter extends Component
{
    public $dark;
    public $email;
    public $phone;
    public $address;
    public $briefs;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $contact = [],
        $siteSettings = true
    ) {
        if (config('atom.static_site')) {
            $this->phone = $contact['phone'] ?? config('atom.contact.phone');
            $this->email = $contact['email'] ?? config('atom.contact.email');
            $this->address = $contact['address'] ?? config('atom.contact.address');
            $this->briefs = $contact['briefs'] ?? config('atom.contact.briefs');
        }
        else if ($siteSettings) {
            $this->phone = isset($contact['phone']) ? $contact['phone'] : SiteSetting::getSetting('phone');
            $this->email = isset($contact['email']) ? $contact['email'] : SiteSetting::getSetting('email');
            $this->address = isset($contact['address']) ? $contact['address'] : SiteSetting::getSetting('address');
            $this->briefs = isset($contact['briefs']) ? $contact['briefs'] : SiteSetting::getSetting('briefs');
        }

        $this->dark = $dark;
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
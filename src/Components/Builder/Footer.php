<?php

namespace Jiannius\Atom\Components\Builder;

use App\Models\SiteSetting;
use Illuminate\View\Component;

class Footer extends Component
{
    public $dark;
    public $phone;
    public $email;
    public $socials;
    public $company;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $contact = [],
        $socials = [],
        $siteSettings = true
    ) {
        if ($siteSettings && !config('atom.static_site')) {
            $this->company = $contact['company'] ?? SiteSetting::getSetting('company');
            $this->phone = $contact['phone'] ?? SiteSetting::getSetting('phone');
            $this->email = $contact['email'] ?? SiteSetting::getSetting('email');
            $this->socials = [];

            SiteSetting::social()
                ->get()
                ->each(fn($setting) => $this->socials[$setting->name] = $socials[$setting->name] ?? $setting->value);
        }
        else {
            $this->company = $contact['company'] ?? null;
            $this->phone = $contact['phone'] ?? null;
            $this->email = $contact['email'] ?? null;
            $this->socials = $socials;
        }

        $this->dark = $dark;
        $this->socials = collect($this->socials)->filter(fn($val) => $val);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.footer');
    }
}
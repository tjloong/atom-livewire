<?php

namespace Jiannius\Atom\Components\Builder;

use Jiannius\Atom\Models\SiteSetting;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Footer extends Component
{
    public $dark;
    public $phone;
    public $email;
    public $socials;
    public $company;
    public $whatsapp;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $contact = [],
        $socials = [],
        $whatsapp = [],
        $siteSettings = true
    ) {
        if (config('atom.static_site')) {
            $this->company = $contact['company'] ?? config('atom.contact.company');
            $this->phone = $contact['phone'] ?? config('atom.contact.phone');
            $this->email = $contact['email'] ?? config('atom.contact.email');
            $this->socials = $socials;
            $this->whatsapp = $whatsapp;
        }
        else if ($siteSettings) {
            $this->company = isset($contact['company']) ? $contact['company'] : SiteSetting::getSetting('company');
            $this->phone = isset($contact['phone']) ? $contact['phone'] : SiteSetting::getSetting('phone');
            $this->email = isset($contact['email']) ? $contact['email'] : SiteSetting::getSetting('email');
            $this->socials = $socials;

            if (!$this->socials) {
                SiteSetting::social()->get()->each(fn($setting) => $this->socials[$setting->name] = $setting->value);
            }

            if (!$this->whatsapp) {
                $this->whatsapp['number'] = Str::replace('+', '', SiteSetting::getSetting('whatsapp'));
                $this->whatsapp['bubble'] = (bool)SiteSetting::getSetting('whatsapp_bubble');
                $this->whatsapp['text'] = SiteSetting::getSetting('whatsapp_text');
            }
        }

        $this->dark = $dark;
        $this->socials = collect($this->socials)->filter(fn($val) => $val);

        if ($this->whatsapp) {
            $this->whatsapp['url'] = 'https://wa.me/' . $this->whatsapp['number'];
            if (!empty($this->whatsapp['text'])) $this->whatsapp['url'] .= '?text=' . urlencode($this->whatsapp['text']);
        }
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
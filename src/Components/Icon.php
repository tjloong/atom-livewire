<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    public $icon;
    public $size;

    public $svgs = [
        'dropdown-caret' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5 text-gray-400"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"></path></svg>',
    ];

    public $aliases = [
        'accounts' => 'address-book',
        'add' => 'plus',
        'address' => 'location-dot',
        'analytics' => 'chart-simple',
        'article' => 'feather-pointed',
        'assign' => 'plus',
        'archive' => 'box-archive',
        'back' => 'arrow-left',
        'billing' => 'credit-card',
        'block' => 'ban',
        'blog' => 'feather-pointed',
        'buy' => 'bag-shopping',
        'cancel' => 'ban',
        'client' => 'address-book',
        'close' => 'xmark',
        'company' => 'building',
        'config' => 'gear',
        'contact' => 'address-book',
        'create' => 'plus',
        'dashboard' => 'chart-line',
        'delete' => 'trash-can',
        'deselect-all' => 'xmark',
        'download' => 'circle-down',
        'duplicate' => 'copy',
        'edit' => 'pen-to-square',
        'email' => 'envelope',
        'enquiries' => 'clipboard-question',
        'enquiry' => 'clipboard-question',
        'excel' => 'file-excel',
        'export' => 'file-export',
        'facebook' => 'brands facebook',
        'finance' => 'magnifying-glass-dollar',
        'financial' => 'magnifying-glass-dollar',
        'google' => 'brands google',
        'help' => 'life-ring',
        'hide' => 'eye-slash',
        'import' => 'cloud-arrow-up',
        'invitation' => 'envelope-circle-check',
        'invoice' => 'file-invoice-dollar',
        'linkedin' => 'brands linkedin',
        'location' => 'location-dot',
        'login' => 'arrow-right-to-bracket',
        'logout' => 'arrow-right-from-bracket',
        'new' => 'plus',
        'open' => 'arrow-up-right-from-square',
        'powerpoint' => 'file-powerpoint',
        'ppt' => 'file-powerpoint',
        'preference' => 'screwdriver-wrench',
        'product' => 'cube',
        'purchase' => 'bag-shopping',
        'publish' => 'cloud-arrow-up',
        'preview' => 'eye',
        'quotation' =>  'file-circle-question',
        'refresh' => 'arrows-rotate',
        'remove' => 'circle-minus',
        'report' => 'magnifying-glass-chart',
        'restore' => 'trash-arrow-up',
        'sales' => 'sack-dollar',
        'save' => 'check',
        'search' => 'magnifying-glass',
        'select-all' => 'check-double',
        'send' => 'paper-plane',
        'seo' => 'searchengin',
        'setting' => 'gear',
        'share' => 'share-nodes',
        'show' => 'eye',
        'signup' => 'user-plus',
        'split' => 'arrows-split-up-and-left',
        'stop' => 'ban',
        'submit' => 'check',
        'support' => 'life-ring',
        'tenant' => 'house-user',
        'transaction' => 'arrow-right-arrow-left',
        'trash' => 'trash-can',
        'unblock' => 'play',
        'undo' => 'arrow-rotate-left',
        'unpublish' => 'arrow-rotate-left',
        'update' => 'pen-to-square',
        'upload' => 'cloud-arrow-up',
        'vendor' => 'people-carry-box',
        'word' => 'file-word',
    ];

    /**
     * Contructor
     */
    public function __construct(
        $name = null,
        $size = '15'
    ) {
        $this->icon = $this->getIcon($name);
        $this->size = str()->replace('px', '', $size);
    }

    /**
     * Get Icon
     */
    public function getIcon($name)
    {
        if ($svg = data_get($this->svgs, strtolower($name))) return $svg;
        
        if ($alias = collect($this->aliases)
            ->first(fn($val, $key) => str($name)->slug()->is($key.'*'))
        ) {
            if ($svg = data_get($this->svgs, $alias)) return $svg;
            else $name = $alias;
        }

        $parts = collect(explode(' ', $name));

        if (!in_array($parts->first(), ['solid', 'regular', 'light', 'thin', 'duotone'])) {
            $parts->prepend('solid');
        }

        return $parts->map(fn($part) => 'fa-'.$part)->join(' ');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.icon');
    }
}
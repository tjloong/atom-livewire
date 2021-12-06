<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class AdminPanel extends Component
{
    public $navs;
    public $bold;
    public $light;
    public $flash;
    public $version;
    public $dropdown;
    public $unverified;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct($dropdown = [], $navs = [], $brand = 'NEW::APP')
    {
        $this->light = explode('::', $brand)[0];
        $this->bold = explode('::', $brand)[1];
        $this->dropdown = collect($dropdown)->map(fn($item) => (object)$item);
        $this->flash = $this->getFlash();
        $this->version = $this->getVersion();
        $this->unverified = request()->user()->mustVerifyEmail && !request()->user()->hasVerifiedEmail();

        $this->navs = collect($navs)->map(function ($nav) {
            $nav = (object)$nav;

            if (isset($nav->dropdown)) {
                $nav->dropdown = collect($nav->dropdown)->map(fn($item) => (object)$item);
                $nav->active = $nav->dropdown->where('active', true)->count() > 0;
            }

            if (!isset($nav->enabled)) $nav->enabled = true;

            return $nav;
        });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.admin-panel');
    }

    /**
     * Get flash message
     * 
     * @return object
     */
    private function getFlash()
    {
        if (!session()->has('flash')) return null;

        $split = explode('::', session('flash'));
        $message = $split[0];
        $type = $split[1] ?? 'info';

        return (object)compact('message', 'type');
    }

    /**
     * Get version number
     * 
     * @return string
     */
    private function getVersion()
    {
        $path = base_path('.git/refs/tags');
        $version = '1.0.0';
    
        if (is_dir($path)) {
            $files = collect(scandir($path))->filter(function($file) {
                return !in_array($file, ['.', '..']);
            });
    
            if ($files->last()) $version = $files->last();
        }
    
        return $version;    
    }
}
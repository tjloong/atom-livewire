<?php

namespace Jiannius\Atom\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class AdminPanel extends Component
{
    public $href;
    public $flash;
    public $version;
    public $isActive;
    public $unverified;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $href = null,
        $active = null
    ) {
        $this->href = $href;
        $this->flash = $this->getFlash();
        $this->version = $this->getVersion();
        $this->unverified = request()->user()->mustVerifyEmail && !request()->user()->hasVerifiedEmail();

        if (is_null($active)) {
            $this->isActive = $href
                ? Str::startsWith(url()->current(), $href)
                : false;
        }
        else $this->isActive = $active;
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
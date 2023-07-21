<?php

namespace Jiannius\Atom\Components\AdminPanel;

use Illuminate\View\Component;

class Index extends Component
{
    public $flash;
    public $version;
    public $unverified;

    // contructor
    public function __construct() 
    {
        $this->flash = $this->getFlash();
        $this->version = $this->getVersion();
        $this->unverified = config('atom.auth.verify')
            && user('email')
            && !user()->hasVerifiedEmail();
    }

    // get flash message
    public function getFlash()
    {
        if (!session()->has('flash')) return null;

        $split = explode('::', session('flash'));
        $message = $split[0];
        $type = $split[1] ?? 'info';

        return (object)compact('message', 'type');
    }

    // get version number
    public function getVersion()
    {
        $path = base_path('.git/refs/tags');
        $version = '1.0.0';
    
        if (is_dir($path)) {
            $files = collect(scandir($path))->filter(function($file) {
                return !in_array($file, ['.', '..']);
            })->values()->all();

            $versions = usort($files, 'version_compare');
    
            if ($versions) $version = collect($versions)->last();
        }
    
        return $version;    
    }

    // render
    public function render()
    {
        return view('atom::components.admin-panel.index');
    }
}
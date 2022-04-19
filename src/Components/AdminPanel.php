<?php

namespace Jiannius\Atom\Components;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class AdminPanel extends Component
{
    public $href;
    public $route;
    public $params;
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
        $route = null,
        $params = null,
        $active = null
    ) {
        $this->href = $href;
        $this->route = $route;
        $this->params = $params;
        $this->flash = $this->getFlash();
        $this->version = $this->getVersion();
        $this->unverified = config('atom.auth.verify') && !request()->user()->hasVerifiedEmail();

        if (is_null($active)) {
            if ($href) $this->isActive = str()->startsWith(url()->current(), $href);
            elseif ($route && Route::has($route)) {
                $segments = explode('.', $route);

                if (count($segments) > 2) array_pop($segments);

                $this->isActive = str()->startsWith(url()->current(), route($route, $params))
                    || current_route() === $route
                    || str(current_route())->is(implode('.', $segments).'.*');
            }
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
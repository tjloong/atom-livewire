<?php

namespace Jiannius\Atom\Components\AdminPanel;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Aside extends Component
{
    public $href;
    public $route;
    public $params;
    public $isActive;

    /**
     * Contructor
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

        if (is_null($active)) {
            if ($href) $this->isActive = str()->startsWith(url()->current(), $href);
            elseif ($route && has_route($route)) {
                $this->isActive = str()->startsWith(url()->current(), route($route, $params))
                    || current_route() === $route
                    || (
                        ($trails = breadcrumbs()->for(current_route())->trails)
                        && ($home = head($trails))
                        && data_get($home, 'route') === route($route)
                    );
            }
        }
        else $this->isActive = $active;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.admin-panel.aside');
    }
}
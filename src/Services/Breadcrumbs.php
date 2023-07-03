<?php

namespace Jiannius\Atom\Services;

class Breadcrumbs
{
    public $for;
    public $trails;
    public $collection;

    // construct
    public function __construct()
    {
        $this->collection = collect();
    }

    // set pointer for $this->for
    // so we can use breadcrumbs()->for($route)->push()
    public function for(string $route): mixed
    {
        $this->for = $route;

        return $this;
    }

    // push trail to collection
    public function push($label, $route = null): mixed
    {
        $sets = $this->collection->get($this->for, collect());
        $sets->push(compact('label', 'route'));
        
        $this->collection->put($this->for, $sets);

        return $this;
    }

    // make breadcrumbs with data for closures
    public function make($params = null): mixed
    {
        $trails = optional($this->collection->get($this->for))->map(function($trail) use ($params) {
            $label = data_get($trail, 'label');
            $route = data_get($trail, 'route');

            if ($label instanceof \Closure) {
                $fx = new \ReflectionFunction($label);
                $args = collect($fx->getParameters())->map(fn($param) => data_get($params, $param->name));
                data_set($trail, 'label', $label(...$args));
            }

            if ($route instanceof \Closure) {
                $fx = new \ReflectionFunction($route);
                $args = collect($fx->getParameters())->map(fn($param) => data_get($params, $param->name));
                data_set($trail, 'route', $route(...$args));
            }
            elseif (has_route($route)) data_set($trail, 'route', route($route));
            
            return $trail;
        });

        $this->trails = optional($trails)->toArray();

        return $this;
    }

    // get arguments name for closures
    public function args()
    {
        if ($trails = $this->collection->get($this->for)) {
            return $trails
                ->filter(fn($trail) => data_get($trail, 'label') instanceof \Closure)
                ->map(fn($trail) => data_get($trail, 'label'))
                ->concat(
                    $trails
                        ->filter(fn($trail) => data_get($trail, 'route') instanceof \Closure)
                        ->map(fn($trail) => data_get($trail, 'route'))
                )
                ->map(function($closure) {
                    $fx = new \ReflectionFunction($closure);
                    return collect($fx->getParameters())
                        ->map(fn($param) => $param->name)
                        ->values()
                        ->all();
                })
                ->collapse()
                ->values()
                ->all();
        }

    }

    // back
    public function back()
    {
        if ($url = collect($this->trails)->pluck('route')->filter()->last()) {
            return redirect($url);
        }
    }
}
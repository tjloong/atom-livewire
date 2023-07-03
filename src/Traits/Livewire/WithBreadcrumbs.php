<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithBreadcrumbs
{
    use WithRoute;
    
    // booted
    public function bootedWithBreadcrumbs()
    {
        $params = collect(breadcrumbs()->for($this->currentRouteName)->args())
            ->mapWithKeys(fn($arg) => [$arg => data_get($this, $arg)]);

        breadcrumbs()->for($this->currentRouteName)->make($params);
    }
}
<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Index extends Component
{
    use AtomComponent;

    public $tab = 'profile';

    // get component property
    public function getComponentProperty() : mixed
    {
        $portal = request()->portal();

        // look for app/settings/component or app/settings/component/index
        if (
            $component = collect([
                "$portal.settings.$this->tab",
                'app.settings.'.$this->tab,
            ])->filter(fn ($val) => Atom::hasLivewireComponent($val))->first()
        ) {
            $path = str()->dotpath($component);
            $key = $this->wirekey($this->tab);

            return (object) compact('path', 'key');
        }
        // eg. app/settings/component/info, we look for app/settings/component, and pass info as the props
        else {
            $name = head(explode('/', $this->tab));
            $slug = last(explode('/', $this->tab));

            if (
                $path = collect([
                    "$portal.settings.$name",
                    "app.settings.$name",
                ])->filter(fn ($val) => Atom::hasLivewireComponent($val))->first()
            ) {
                $key = $this->wirekey($name);
                $props = ['slug' => $slug];

                return (object) compact('path', 'key', 'props');
            }
            else {
                abort(404);
            }
        }
    }
}
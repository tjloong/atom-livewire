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
        // look for app/settings/component or app/settings/component/index
        if (
            $component = collect([
                'app.settings.'.$this->tab,
                'app.settings.'.$this->tab.'.index',
            ])->filter(fn($val) => Atom::hasLivewireComponent($val))->first()
        ) {
            $path = str()->dotpath($component);
            $key = $this->wirekey($this->tab);

            return (object) compact('path', 'key');
        }
        // eg. app/settings/component/info, we look for app/settings/component, and pass info as the props
        else {
            $name = head(explode('/', $this->tab));
            $slug = last(explode('/', $this->tab));
            $path = 'app.settings.'.$name;

            if (Atom::hasLivewireComponent($path)) {
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
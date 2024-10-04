<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $tab = 'profile';

    // get component property
    public function getComponentProperty() : mixed
    {
        if (atom()->hasLivewireComponent('app.settings.'.$this->tab)) {
            $path = str()->dotpath('app.settings.'.$this->tab);
            $key = $this->wirekey($this->tab);

            return (object) compact('path', 'key');
        }
        else {
            $name = head(explode('/', $this->tab));
            $slug = last(explode('/', $this->tab));
            $path = 'app.settings.'.$name;
            $params = ['slug' => $slug];
            $key = $this->wirekey($name);

            return atom()->hasLivewireComponent($path) ? (object) compact('path', 'params', 'key') : null;
        }
    }
}
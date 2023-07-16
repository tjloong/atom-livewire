<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;

    public $labels;
    public $isChildren;

    // sort
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('Sorted Labels');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.label.listing');
    }
}
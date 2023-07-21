<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Listing extends Component
{
    use WithPopupNotify;

    public $labels;

    // sort
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('Sorted Labels');
    }
}
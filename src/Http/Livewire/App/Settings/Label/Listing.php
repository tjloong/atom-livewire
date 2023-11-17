<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Jiannius\Atom\Component;

class Listing extends Component
{
    public $labels;

    protected $listeners = [
        'labelCreated' => '$refresh',
        'labelUpdated' => '$refresh',
        'labelDeleted' => '$refresh',
    ];

    // sort
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('common.alert.sorted');
    }
}
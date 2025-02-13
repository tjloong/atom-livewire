<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Listing extends Component
{
    public $labels;

    // sort
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->labels = model('label')->whereIn('id', $data)->sequence()->get();

        $this->popup('app.label.sorted');
    }
}
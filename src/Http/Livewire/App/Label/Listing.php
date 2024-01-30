<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Component;

class Listing extends Component
{
    public $labels;
    public $children;

    // sort
    public function sort($data): void
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('app.label.sorted');
    }

    // set children
    public function setChildren($id) : void
    {
        if (data_get($this->children, 'parent_id') === $id) $this->children = null;
        else {
            $this->children = [
                'parent_id' => $id,
                'labels' => model('label')
                    ->where('parent_id', $id)
                    ->orderBy('seq')
                    ->orderBy('id')
                    ->get(),
            ];
        }
    }
}
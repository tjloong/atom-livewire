<?php

namespace App\Http\Livewire\App\Banner;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'type' => [],
        'status' => [],
        'placement' => [],
    ];

    protected $listeners = [
        'closeBanner' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('banner')->when(!$this->tableOrderBy, fn($q) => $q->oldest('seq')->latest('id'));
    }

    // sort
    public function sort($data) : void
    {
        foreach ($data as $seq => $id) {
            model('banner')->find($id)->fill(['seq' => $seq])->save();
        }

        $this->popup('app.label.sorted');
    }

    // delete
    public function delete() : void
    {
        if ($this->tableCheckboxes) {
            model('banner')->whereIn('id', $this->tableCheckboxes)->delete();
            $this->reset('tableCheckboxes');
        }
    }
}
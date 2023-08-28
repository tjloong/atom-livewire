<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;
    public $bannerId;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'updateBanner' => 'setBannerId',
        'bannerSaved' => 'setBannerId',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('banner')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->oldest('seq')->latest('id'));
    }

    // set banner id
    public function setBannerId($id = null) : void
    {
        $this->fill(['bannerId' => $id ?: null]);
    }

    // sort
    public function sort($data) : void
    {
        foreach ($data as $seq => $id) {
            model('banner')->find($id)->fill(['seq' => $seq])->save();
        }
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('banner')->whereIn('id', $this->checkboxes)->delete();
            $this->resetCheckboxes();
        }
    }
}
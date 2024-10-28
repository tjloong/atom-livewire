<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Shareable extends Component
{
    use AtomComponent;

    public $model;
    public $enabled;
    public $shareable;
    public $mailable = false;

    protected function validation() : array
    {
        return [
            'shareable.valid_for' => ['nullable'],
        ];
    }

    public function updatedEnabled($val) : void
    {
        if (!$val && $this->shareable) {
            $this->shareable->delete();
        }
        else if ($val && !$this->shareable) {
            $this->shareable = $this->model->shareable()->create([]);
        }

        $this->refresh();
    }

    public function updatedShareable() : void
    {
        $this->shareable->save();
    }

    public function open() : void
    {
        if (!$this->model) return;

        $this->shareable = $this->model->shareable;
        $this->enabled = !empty($this->shareable);
    }

    public function regenerate() : void
    {
        $this->shareable->delete();
        $this->shareable = $this->model->shareable()->create([]);
    }

    public function mail() : void
    {
        Atom::modal('app.sendmail.composer')->show(['shareable_id' => $this->shareable->id]);
        Atom::modal('app.shareable')->close();
    }
}
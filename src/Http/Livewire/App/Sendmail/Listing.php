<?php

namespace Jiannius\Atom\Http\Livewire\App\Sendmail;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => [],
        'tags' => [],
    ];

    protected $listeners = [
        'closeSendmail' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('sendmail')->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }

    // delete
    public function delete() : void
    {
        if ($this->tableCheckboxes) {
            model('sendmail')->whereIn('id', $this->checkboxes)->delete();
        }

        $this->reset('tableCheckboxes');
    }
}
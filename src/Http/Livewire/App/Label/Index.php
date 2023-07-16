<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public $type;
    public $header;

    protected $listeners = [
        'updateOrCreate',
        'refresh' => '$refresh',
    ];

    // get title property
    public function getTitleProperty(): string
    {
        if ($this->header) return $this->header;

        return $this->type
            ? str($this->type)->headline()->plural()->toString()
            : 'Labels';
    }

    // get labels property
    public function getLabelsProperty(): Collection
    {
        return model('label')
            ->readable()
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->whereNull('parent_id')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    // update or create
    public function updateOrCreate($data = null): void
    {
        $this->emit('open', $data)->to(atom_lw('app.label.form'));
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.label');
    }
}
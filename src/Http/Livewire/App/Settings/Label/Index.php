<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $type;

    protected $listeners = [
        'update',
        'labelSaved' => '$refresh',
    ];

    // mount
    public function mount($params = []): void
    {
        $this->type = $this->type ?? head($params);
    }

    // get title property
    public function getTitleProperty(): string
    {
        return $this->type
            ? str($this->type)->headline()->plural()->toString()
            : 'Labels';
    }

    // get labels property
    public function getLabelsProperty(): mixed
    {
        return model('label')
            ->readable()
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->whereNull('parent_id')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    // update
    public function update($data = null): void
    {
        $this->emitTo('app.settings.label.update', 'open', $data);
    }
}
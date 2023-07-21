<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Illuminate\Database\Eloquent\Collection;
use Jiannius\Atom\Component;

class Index extends Component
{
    public $type;

    protected $listeners = [
        'updateOrCreate',
        'refresh' => '$refresh',
    ];

    // mount
    public function mount($params = []): void
    {
        parent::mount();

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
        $this->emit('open', $data)->to('app.settings.label.form');
    }
}
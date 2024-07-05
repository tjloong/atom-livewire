<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Share extends Component
{
    use WithForm;

    public $share;

    public $methods = [
        'whatsapp' => true,
        'telegram' => true,
        'email' => true,
    ];

    // validation
    protected function validation() : array
    {
        return [
            'share.is_enabled' => ['nullable'],
            'share.valid_for' => ['nullable'],
        ];
    }

    // get enabled methods property
    public function getEnabledMethodsProperty() : array
    {
        return collect($this->methods)->filter()->map(fn($val, $key) => $key)->values()->all();
    }

    // updated share
    public function updatedShare() : void
    {
        $this->share->save();
    }

    // load
    public function load($data) : void
    {
        $entity = app(get($data, 'model'))->find(get($data, 'id'));
        $this->share = $entity->share ?? $entity->share()->create(['is_enabled' => true]);
    }

    // cleanup
    public function cleanup() : void
    {
        $this->reset('share');
    }

    // regenerate
    public function regenerate() : void
    {
        $this->share->fill(['ulid' => null])->save();        
    }
}
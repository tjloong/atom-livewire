<?php

namespace Jiannius\Atom\Http\Livewire\App\Share;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $share;
    public $parent;

    protected $listeners = [
        'share' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'share.valid_for' => ['nullable'],
            'share.is_enabled' => ['nullable'],
        ];
    }

    // updated share
    public function updatedShare() : void
    {
        $this->share->save();
    }

    // open
    public function open($model = null, $id = null) : void
    {
        $this->parent = $model && $id
            ? model($model)->find($id)
            : $this->parent;

        $this->share = $this->parent->fresh()->share 
            ?? $this->parent->share()->create(['is_enabled' => true]);

        $this->dispatchBrowserEvent('share-update-open');
    }

    // regenerate
    public function regenerate() : void
    {
        $this->share->fill(['ulid' => null])->save();
    }
}
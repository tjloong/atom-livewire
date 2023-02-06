<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;

    public $type;
    public $header;
    public $maxDepth = 1;

    protected $listeners = [
        'open',
        'delete',
        'refresh' => '$refresh',
    ];

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        if ($this->header) return $this->header;

        return $this->type
            ? str($this->type)->headline()->plural()->toString()
            : 'Labels';
    }

    /**
     * Get labels property
     */
    public function getLabelsProperty()
    {
        return model('label')
            ->with('children')
            ->when(model('label')->enabledHasTenantTrait, fn($q) => $q->belongsToTenant())
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->whereNull('parent_id')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    /**
     * Open
     */
    public function open($data)
    {
        $this->emitTo(lw('app.preferences.label.form-modal'), 'open', $data);
    }

    /**
     * Sort
     */
    public function sort($data)
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('Sorted Labels');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        optional(model('label')->find($id))->delete();

        $this->emit('refresh');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences.label');
    }
}
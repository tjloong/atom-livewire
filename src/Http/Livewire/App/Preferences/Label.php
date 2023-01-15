<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Label extends Component
{
    use WithPopupNotify;

    public $type;
    public $header;
    public $sublabel = false;

    protected $listeners = ['refresh' => '$refresh'];

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
            ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->whereNull('parent_id')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    /**
     * Open
     */
    public function open($id = null, $parentId = null)
    {
        $this->emitTo(lw('app.settings.system.label-form-modal'), 'open', [
            'id' => $id,
            'type' => $this->type,
            'parent_id' => $parentId,
        ]);
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
        optional($this->labels->firstWhere('id', $id))->delete();

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
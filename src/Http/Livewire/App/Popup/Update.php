<?php

namespace Jiannius\Atom\Http\Livewire\App\Popup;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $popup;
    public $inputs;

    protected $listeners = [
        'createPopup' => 'create',
        'updatePopup' => 'update',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'popup.name' => ['required' => 'Popup name is required.'],
            'popup.href' => ['nullable'],
            'popup.content' => ['nullable'],
            'popup.bg_color' => ['nullable'],
            'popup.image_id' => ['nullable'],
            'popup.start_at' => ['nullable'],
            'popup.end_at' => ['nullable'],
        ];
    }

    // create
    public function create() : void
    {
        $this->popup = model('popup')->fill(['start_at' => now()]);
        $this->open();
    }

    // update
    public function update($id = null) : void
    {
        $this->popup = model('popup')->find($id);
        $this->open();
    }

    // open
    public function open() : void
    {
        if ($this->popup) {
            $this->resetValidation();
            $this->modal(id: 'popup-update');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setPopupId');
        $this->modal(false, 'popup-update');
    }

    // duplicate
    public function duplicate() : void
    {
        model('popup')->create([
            'name' => $this->popup->name.' Copy',
            'href' => $this->popup->href,
            'content' => $this->popup->content,
            'bg_color' => $this->popup->bg_color,
            'image_id' => $this->popup->image_id,
        ]);

        $this->emit('popupCreated');
        $this->close();
    }

    // delete
    public function delete() : void
    {
        $this->popup->delete();
        $this->emit('popupDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->popup->save();

        if ($this->popup->wasRecentlyCreated) $this->emit('popupCreated');
        else $this->emit('popupUpdated');

        $this->close();
    }
}
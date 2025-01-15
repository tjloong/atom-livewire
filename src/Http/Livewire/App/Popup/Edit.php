<?php

namespace Jiannius\Atom\Http\Livewire\App\Popup;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $popup;
    public $inputs;

    protected $listeners = [
        'editPopup' => 'open',
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

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->popup = $id
            ? model('popup')->find($id)
            : model('popup')->fill(['start_at' => now(), ...$data])
        ) {
            $this->resetValidation();
            $this->overlay();
        }
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

        $this->overlay(false);
    }

    // delete
    public function delete() : void
    {
        $this->popup->delete();
        $this->overlay(false);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->popup->save();
        $this->overlay(false);
    }
}
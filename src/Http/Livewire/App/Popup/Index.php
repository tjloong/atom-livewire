<?php

namespace Jiannius\Atom\Http\Livewire\App\Popup;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $popupId;

    protected $listeners = [
        'setPopupId',
        'updatePopup' => 'setPopupId',
    ];

    // set popup id
    public function setPopupId($id = null) : void
    {
        $this->fill(['popupId' => $id ?: null]);
    }
}
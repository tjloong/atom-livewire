<?php

namespace Jiannius\Atom\Http\Livewire\App\Sendmail;

use Livewire\Component;

class Edit extends Component
{
    public $sendmail;

    protected $listeners = [
        'editSendmail' => 'open',
    ];

    // open
    public function open($data = []) : void
    {
        if ($this->sendmail = model('sendmail')->where('ulid', get($data, 'ulid'))->first()) {
            $this->overlay();
        }
    }

    // delete
    public function delete() : void
    {
        $this->sendmail->delete();
        $this->reset('sendmail');
        $this->overlay(false);
    }
}
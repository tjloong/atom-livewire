<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $signupId;

    protected $listeners = [
        'setSignupId',
        'updateSignup' => 'setSignupId',
    ];

    // setSignupId
    public function setSignupId($id = null) : void
    {
        $this->fill(['signupId' => $id ?: null]);
    }
}
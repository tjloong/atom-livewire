<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $enquiryId;

    protected $listeners = [
        'setEnquiryId',
        'updateEnquiry' => 'setEnquiryId',
    ];

    // set enquiry id
    public function setEnquiryId($id = null) : void
    {
        $this->fill(['enquiryId' => $id ?: null]);
    }
}
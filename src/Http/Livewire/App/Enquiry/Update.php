<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $inputs;
    public $enquiry;

    protected $listeners = [
        'updateEnquiry' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'enquiry.remark' => ['nullable'],
            'inputs.status' => ['required' => 'Status is required.'],
        ];
    }

    // open
    public function open($id) : void
    {
        if ($this->enquiry = model('enquiry')->find($id)) {
            $this->fill([
                'inputs.status' => $this->enquiry->status->value,
            ]);
    
            $this->openDrawer('enquiry-update');
        }
    }

    // close
    public function close() : void
    {
        $this->closeDrawer('enquiry-update');
    }

    // delete
    public function delete() : void
    {
        $this->enquiry->delete();
        $this->emit('enquiryDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->enquiry->fill([
            'status' => data_get($this->inputs, 'status'),
        ])->save();

        if ($this->enquiry->wasRecentlyCreated) $this->emit('enquiryCreated');
        else $this->emit('enquiryUpdated');
        
        $this->close();
    }
}
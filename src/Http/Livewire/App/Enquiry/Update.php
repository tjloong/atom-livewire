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
    
            $this->dispatchBrowserEvent('enquiry-update-open');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('enquirySaved');
        $this->dispatchBrowserEvent('enquiry-update-close');
    }

    // delete
    public function delete() : void
    {
        $this->enquiry->delete();
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->enquiry->fill([
            'status' => data_get($this->inputs, 'status'),
        ])->save();
        
        $this->close();
    }
}
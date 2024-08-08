<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $inputs;
    public $enquiry;

    protected $listeners = [
        'editEnquiry' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'enquiry.notes' => ['nullable'],
            'inputs.status' => ['required' => 'Status is required.'],
        ];
    }

    // open
    public function open($id) : void
    {
        if ($this->enquiry = model('enquiry')->find($id)) {
            $this->fill(['inputs.status' => $this->enquiry->status->value]);
            $this->overlay();
        }
    }

    // delete
    public function delete() : void
    {
        $this->enquiry->delete();
        $this->overlay(false);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->enquiry->fill([
            'status' => data_get($this->inputs, 'status'),
        ])->save();

        $this->overlay(false);
    }
}
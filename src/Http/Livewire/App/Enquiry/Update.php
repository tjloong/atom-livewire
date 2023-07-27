<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Update extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $enquiry;
    public $inputs;

    protected $listeners = ['open'];

    // validation
    protected function validation(): array
    {
        return [
            'enquiry.remark' => ['nullable'],
            'inputs.status' => ['required' => 'Status is required.'],
        ];
    }

    // open
    public function open($id): void
    {
        $this->enquiry = model('enquiry')->readable()->findOrFail($id);

        $this->fill([
            'inputs.status' => $this->enquiry->status->value,
        ]);

        $this->dispatchBrowserEvent('enquiry-update-open');
    }

    // close
    public function close(): void
    {
        $this->emit('enquiryUpdateClosed');
        $this->dispatchBrowserEvent('enquiry-update-close');
    }

    // delete
    public function delete(): void
    {
        $this->enquiry->delete();
        $this->close();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $this->enquiry->fill([
            'status' => data_get($this->inputs, 'status'),
        ])->save();
        
        $this->close();
    }
}
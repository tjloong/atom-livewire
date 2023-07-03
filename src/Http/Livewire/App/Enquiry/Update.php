<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithBreadcrumbs;
    use WithForm;
    use WithPopupNotify;

    public $enquiry;

    // validation
    protected function validation(): array
    {
        return [
            'enquiry.remark' => ['nullable'],
            'enquiry.status' => ['required' => 'Status is required.'],
        ];
    }

    // mount
    public function mount($enquiryId): void
    {
        $this->enquiry = model('enquiry')->readable()->findOrFail($enquiryId);
    }

    // delete
    public function delete(): mixed
    {
        $this->enquiry->delete();

        return breadcrumbs()->back();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $this->enquiry->save();
        
        $this->popup('Enquiry Updated.');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.enquiry.update');
    }
}
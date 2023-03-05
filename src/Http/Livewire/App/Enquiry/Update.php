<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $enquiry;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'enquiry.remark' => ['nullable'],
            'enquiry.status' => ['required' => 'Status is required.'],
        ];
    }

    /**
     * Mount
     */
    public function mount($enquiryId): void
    {
        $this->enquiry = model('enquiry')->readable()->findOrFail($enquiryId);

        breadcrumbs()->push($this->enquiry->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->enquiry->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();
        $this->enquiry->save();
        $this->popup('Enquiry Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.enquiry.update');
    }
}
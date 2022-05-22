<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;

class Update extends Component
{
    public $enquiry;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'enquiry.remark' => 'nullable',
            'enquiry.status' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'enquiry.status.required' => __('Enquiry status is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount($enquiry)
    {
        $this->enquiry = model('enquiry')->findOrFail($enquiry);
        breadcrumbs()->push($this->enquiry->name);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->enquiry->save();
        
        $this->dispatchBrowserEvent('toast', ['message' => 'Enquiry Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->enquiry->delete();
        session()->flash('flash', 'Enquiry Deleted');
        return redirect()->route('app.enquiry.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.enquiry.update');
    }
}
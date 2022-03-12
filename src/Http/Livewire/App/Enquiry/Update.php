<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;

class Update extends Component
{
    public $enquiry;

    protected $rules = [
        'enquiry.remark' => 'nullable',
        'enquiry.status' => 'required',
    ];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->enquiry = model('enquiry')->findOrFail($id);
        breadcrumbs()->push($this->enquiry->name);
    }

    /**
     * Submit
     */
    public function submit()
    {
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
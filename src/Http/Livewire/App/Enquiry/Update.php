<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;
use Jiannius\Atom\Models\Enquiry;

class Update extends Component
{
    public Enquiry $enquiry;

    protected $rules = [
        'enquiry.remark' => 'nullable',
        'enquiry.status' => 'required',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        breadcrumbs()->push($this->enquiry->name);
    }

    /**
     * Save
     */
    public function save()
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
        return redirect()->route('enquiry.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.enquiry.update');
    }
}
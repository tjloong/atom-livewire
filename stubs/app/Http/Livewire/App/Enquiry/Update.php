<?php

namespace App\Http\Livewire\App\Enquiry;

use App\Models\Enquiry;
use Livewire\Component;

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
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.enquiry.update');
    }

    /**
     * Save enquiry
     * 
     * @return void
     */
    public function save()
    {
        $this->enquiry->save();
        $this->dispatchBrowserEvent('toast', ['message' => 'Enquiry Updated', 'type' => 'success']);
    }

    /**
     * Delete enquiry
     * 
     * @return void
     */
    public function delete()
    {
        $this->enquiry->delete();
        session()->flash('flash', 'Enquiry Deleted');
        return redirect()->route('enquiry.listing');
    }
}
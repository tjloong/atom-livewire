<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;
    public $enquiryId;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'updateEnquiry' => 'setEnquiryId',
        'enquirySaved' => 'setEnquiryId',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('enquiry')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // set enquiry id
    public function setEnquiryId($id = null) : void
    {
        $this->fill(['enquiryId' => $id ?: null]);
    }

    // export
    public function export(): mixed
    {
        $path = storage_path('enquiries-'.time().'.xlsx');

        excel($this->query->get())->export($path, fn($enquiry) => [
            'Date' => $enquiry->created_at->toDatetimeString(),
            'Name' => $enquiry->name,
            'Phone' => $enquiry->phone,
            'Email' => $enquiry->email,
            'Message' => $enquiry->message,
            'Status' => $enquiry->status,
        ]);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
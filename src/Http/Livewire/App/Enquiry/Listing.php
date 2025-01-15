<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => [],
        'created_at' => null,
    ];

    protected $listeners = [
        'closeEnquiry' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('enquiry')->when(!$this->tableOrderBy, fn($q) => $q->latest());
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
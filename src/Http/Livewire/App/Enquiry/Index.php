<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'enquiryUpdateClosed' => '$refresh',
    ];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('enquiry')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // update
    public function update($id): void
    {
        $this->emitTo('app.enquiry.update', 'open', $id);
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
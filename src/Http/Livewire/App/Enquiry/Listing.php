<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
    ];

    // mount
    public function mount(): void
    {
        breadcrumbs()->home($this->title);
    }

    // get title property
    public function getTitleProperty(): string
    {
        return 'Enquiries';
    }

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('enquiry')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // get table columns
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Date',
                'sort' => 'created_at',
                'date' => $query->created_at,
            ],
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.enquiry.update', [$query->id]),
            ],
            [
                'name' => 'Phone',
                'label' => $query->phone,
            ],
            [
                'name' => 'Email',
                'label' => $query->email,
            ],
            [
                'name' => 'Status',
                'status' => $query->status,
            ],
        ];
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

    // render
    public function render(): mixed
    {
        return atom_view('app.enquiry.listing');
    }
}
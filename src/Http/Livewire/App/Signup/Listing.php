<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Component;
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
        'signupUpdated' => '$refresh',
        'signupDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('signup')
            ->filter($this->filters)
            ->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }

    // export
    public function export() : mixed
    {
        $path = storage_path('signups-'.time().'.xlsx');

        excel($this->query->get())->export($path, fn($signup) => [
            'Date' => $signup->created_at->toDatetimeString(),
            'Name' => $signup->user->name,
            'Email' => $signup->user->email,
            'Agreed to T&C' => $signup->agree_tnc ? 'Yes' : 'No',
            'Agreed to Marketing' => $signup->agree_promo ? 'Yes' : 'No',
            'Status' => $signup->status->label(),
        ]);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
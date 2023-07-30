<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;

    public $sort;
    public $signupId;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    // get signup property
    public function getSignupProperty(): mixed
    {
        return $this->signupId
            ? model('signup')->find($this->signupId)
            : null;
    }

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('signup')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // clear
    public function clear(): void
    {
        $this->reset('signupId');
    }

    // export
    public function export(): mixed
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
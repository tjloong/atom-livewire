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

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('signup')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    // setSignupId
    public function setSignupId($id = null): void
    {
        $this->fill(['signupId' => $id ?: null]);
        if ($id) $this->emit('updateSignup', $id);
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
<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $tier;
    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    // get query property
    public function getQueryProperty()
    {
        return model('user')
            ->tier('signup')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest('signup_at'));
    }

    // get table columns
    public function getTableColumns($query)
    {
        return [
            [
                'column_name' => 'Name',
                'column_sort' => 'name',
                'label' => $query->name,
                'href' => route('app.signup.update', [$query->id]),
            ],

            [
                'column_name' => 'Email',
                'label' => $query->email,
            ],

            has_table('plans')
                ? [
                    'column_name' => 'Plan',
                    'tags' => $query->subscriptions
                        ->map(fn($sub) => $sub->price->plan->name)
                        ->unique()
                        ->toArray(),
                ] : null,

            [
                'column_name' => 'Status',
                'status' => $query->status,
            ],

            [
                'column_name' => 'Sign-Up Date',
                'column_sort' => 'signup_at',
                'date' => $query->signup_at,
            ],
        ];
    }

    // export
    public function export()
    {
        return excel(
            $this->query->get(),
            ['filename' => 'signups-'.time()],
            fn($user) => [
                'Date' => $user->signup_at->toDatetimeString(),
                'Name' => $user->name,
                'Email' => $user->email,
                'Agreed to T&C' => data_get($user->data, 'signup.agree_tnc') ? 'Yes' : 'No',
                'Agreed to Marketing' => data_get($user->data, 'signup.agree_marketing') ? 'Yes' : 'No',
                'Status' => $user->status,
                'Blocked Date' => optional($user->blocked_at)->toDatetimeString(),
            ]
        );
    }

    // render
    public function render()
    {
        return atom_view('app.signup.listing');
    }
}
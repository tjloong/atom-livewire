<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $tier;
    public $sortBy = 'signup_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $queryString = [
        'sortBy' => ['except' => 'signup_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
        ]],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Sign-Ups');
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return model('user')
            ->tier('signup')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows)
            ->through(fn($user) => [
                [
                    'column_name' => 'Name',
                    'column_sort' => 'name',
                    'label' => $user->name,
                    'href' => route('app.signup.view', [$user->id]),
                ],
    
                [
                    'column_name' => 'Email',
                    'label' => $user->email,
                ],
    
                enabled_module('plans')
                    ? [
                        'column_name' => 'Plan',
                        'tags' => $user->subscriptions
                            ->map(fn($sub) => $sub->planPrice->plan->name)
                            ->unique()
                            ->toArray(),
                    ] : null,
    
                [
                    'column_name' => 'Status',
                    'status' => $user->status,
                ],
    
                [
                    'column_name' => 'Sign-Up Date',
                    'column_sort' => 'signup_at',
                    'date' => $user->signup_at,
                ],
            ]);
    }

    /**
     * Export
     */
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

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.signup.listing');
    }
}
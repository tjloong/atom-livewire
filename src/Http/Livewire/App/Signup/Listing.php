<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters' => ['except' => ['search' => '']],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
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
            ->whereHas('signup', fn($q) => $q->filter($this->filters))
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.signup.listing', [
            'users' => $this->users->paginate(30),
        ]);
    }

    /**
     * Export
     */
    public function export()
    {
        $filename = 'signups-' . rand(1000, 9999) . '.xlsx';
        $users = $this->users->get();

        return export_to_excel($filename, $users, fn($user) => [
            'Date' => $user->created_at->toDatetimeString(),
            'Name' => $user->name,
            'Phone' => $user->signup->phone,
            'Email' => $user->email ?? $user->signup->email,
            'Status' => $user->signup->status,
            'Blocked Date' => optional($user->signup->blocked_at)->toDatetimeString(),
        ]);
    }
}
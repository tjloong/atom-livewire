<?php

namespace Jiannius\Atom\Http\Livewire\App\Invitation;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $fullpage;

    /**
     * Mount
     */
    public function mount(): void
    {
        if ($this->fullpage = current_route('app.invitation.listing')) {
            breadcrumbs()->push('Invitations');
        }
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): mixed
    {
        return model('invitation')->readable()->latest();
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Email',
                'label' => $query->email,
            ],

            [
                'name' => 'Status',
                'class' => 'text-right',
                'status' => $query->status,
            ],

            [
                'name' => 'Invited Date',
                'class' => 'text-right',
                'date' => $query->created_at,
            ],

            [
                'name' => 'Invited By',
                'label' => optional($query->createdBy)->name ?? '--',
            ]
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.invitation.listing');
    }
}
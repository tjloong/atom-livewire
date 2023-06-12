<?php

namespace Jiannius\Atom\Http\Livewire\App\Payment;

use Livewire\Component;

class Create extends Component
{
    public $banner;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->banner = model('banner')->fill([
            'is_active' => true,
        ]);

        breadcrumbs()->push('Create Banner');
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.banner.create');
    }
}
<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Livewire\Component;

class Create extends Component
{
    use WithBreadcrumbs;

    public $banner;

    protected $listeners = ['submitted'];

    // mount
    public function mount(): void
    {
        $this->banner = model('banner')->fill([
            'is_active' => true,
        ]);
    }

    // submitted
    public function submitted(): mixed
    {
        return breadcrumbs()->back();
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.banner.create');
    }
}
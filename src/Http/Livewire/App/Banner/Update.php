<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $banner;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($bannerId): void
    {
        $this->banner = model('banner')->findOrFail($bannerId);

        breadcrumbs()->push($this->banner->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->banner->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submitted
     */
    public function submitted()
    {
        $this->popup('Banner Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.banner.update');
    }
}
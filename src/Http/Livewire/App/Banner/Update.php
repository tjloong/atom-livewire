<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithBreadcrumbs;
    use WithPopupNotify;
    
    public $banner;

    protected $listeners = ['submitted'];

    // mount
    public function mount($bannerId): void
    {
        $this->banner = model('banner')->findOrFail($bannerId);
    }

    // delete
    public function delete(): mixed
    {
        $this->banner->delete();

        return breadcrumbs()->back();
    }

    // submitted
    public function submitted()
    {
        $this->popup('Banner Updated.');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.banner.update');
    }
}
<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$page->name" back/>
    @if ($component = livewire_name('app/page/update/'.$this->slug))
        @livewire($component, compact('page'))
    @else
        @livewire('atom.app.page.update.content', compact('page'))
    @endif
</div>
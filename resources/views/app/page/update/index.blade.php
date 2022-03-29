<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$page->name" back/>
    @if ($component = livewire_name('app/page/update/'.$this->slug))
        @livewire($component, compact('tab', 'page'))
    @else
        @livewire('atom.app.page.update.content', compact('page'))
    @endif
</div>
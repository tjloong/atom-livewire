<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$page->name" back/>
    @if ($component = get_livewire_component($this->slug, 'app/page/update'))
        @livewire($component, compact('page'))
    @else
        @livewire('atom.app.page.update.content', compact('page'))
    @endif
</div>
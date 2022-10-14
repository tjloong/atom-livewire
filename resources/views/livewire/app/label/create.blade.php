<div class="max-w-lg mx-auto">
    <x-page-header title="Create Label" back/>

    @if ($com = lw('app.label.update.info'))
        @livewire($com, ['label' => $label, 'locales' => $this->locales])
    @endif
</div>
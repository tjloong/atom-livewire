<div class="max-w-lg mx-auto">
    <x-page-header title="Create Label" back/>

    @if ($component = livewire_name('app/label/update/general'))
        @livewire($component, ['label' => $label, 'locales' => $this->locales])
    @endif
</div>
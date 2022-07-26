<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$label->locale('name')" back>
        <x-button.delete inverted title="Delete Label" message="Are you sure to delete this label?"/>
    </x-page-header>

    <div class="grid gap-6">
        <div>
            @if ($general = livewire_name('app/label/update/general'))
                @livewire($general, ['label' => $label, 'locales' => $this->locales])
            @endif
        </div>
    
        @if ($this->enableChildren)
            <div>
                @if ($children = livewire_name('app/label/update/children'))
                    @livewire($children, ['label' => $label, 'locales' => $this->locales])
                @endif
            </div>
        @endif
    </div>
</div>
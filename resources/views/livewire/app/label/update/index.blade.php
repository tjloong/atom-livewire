<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$label->locale('name')" back>
        @if (!data_get($label, 'data.is_locked'))
            <x-button.delete inverted title="Delete Label" message="Are you sure to delete this label?"/>
        @endif
    </x-page-header>

    <div class="grid gap-6">
        <div>
            @if ($info = lw('app.label.update.info'))
                @livewire($info, ['label' => $label, 'locales' => $this->locales])
            @endif
        </div>

        @if ($this->enableChildren)
            <div>
                @if ($children = lw('app.label.update.children'))
                    @livewire($children, ['label' => $label, 'locales' => $this->locales])
                @endif
            </div>
        @endif
    </div>
</div>
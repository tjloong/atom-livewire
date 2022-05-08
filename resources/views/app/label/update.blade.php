<div class="max-w-screen-sm mx-auto">
    <x-page-header title="{{ $label->name }}" back>
        <x-button.delete inverted title="Delete Label" message="Are you sure to delete this label?"/>
    </x-page-header>

    @if ($component = livewire_name('app/label/form'))
        @livewire($component, ['label' => $label])
    @endif
</div>
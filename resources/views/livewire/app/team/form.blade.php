<x-form>
    <x-form.group>
        <x-form.text wire:model.defer="team.name" label="Team Name"/>
        <x-form.textarea wire:model.defer="team.description"/>
    </x-form.group>
</x-form>
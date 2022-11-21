<x-modal uid="team-form-modal" icon="people-group" :header="optional($team)->id ? 'Update Team' : 'Create Team'" class="max-w-screen-sm">
    <div class="flex flex-col gap-6">
        <x-form.text 
            label="Team Name"
            wire:model.defer="team.name" 
            :error="$errors->first('team.name')" 
            required
        />
    
        <x-form.textarea 
            label="Description"
            wire:model.defer="team.description"
        />
    </div>

    <x-slot:foot>
        <x-button.submit type="button" wire:click="submit"/>
    </x-slot:foot>
</x-modal>
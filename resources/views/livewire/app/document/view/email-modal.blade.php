<x-form modal icon="paper-plane"
    uid="email-modal" 
    header="Send Email" 
>
    @if ($inputs)
        <x-form.group cols="2">
            <x-form.email wire:model.defer="email.from.email" label="Sender Email"/>
            <x-form.text wire:model.defer="email.from.name" label="Sender Name"/>
        </x-form.group>

        <x-form.group>
            <x-form.email wire:model.defer="email.to" :options="$this->emails->toArray()" multiple/>
            <x-form.email wire:model.defer="email.cc" :options="$this->emails->toArray()" multiple/>
            <x-form.text wire:model.defer="email.subject"/>
            <x-form.textarea wire:model.defer="email.body" rows="10"/>
            <x-form.email wire:model.defer="email.bcc" label="Send a copy to" multiple/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit label="Send Email"/>
        </x-slot:foot>
    @endif
</x-form>

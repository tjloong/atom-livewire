<x-form modal icon="paper-plane" uid="email-modal" header="Send Email">
    @if ($inputs)
        <x-form.group cols="2">
            <x-form.email wire:model.defer="inputs.from.email" label="Sender Email"/>
            <x-form.text wire:model.defer="inputs.from.name" label="Sender Name"/>
        </x-form.group>

        <x-form.group>
            <x-form.email wire:model="inputs.to" :options="data_get($this->options, 'emails')" multiple/>
            <x-form.email wire:model="inputs.cc" :options="data_get($this->options, 'emails')" multiple/>
            <x-form.text wire:model.defer="inputs.subject"/>
            <x-form.textarea wire:model.defer="inputs.body" rows="10"/>
            <x-form.email wire:model="inputs.bcc" label="Send a copy to" multiple/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit label="Send Email"/>
        </x-slot:foot>
    @endif
</x-form>

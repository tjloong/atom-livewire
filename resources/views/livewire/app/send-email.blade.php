<x-form id="send-email" title="Send Email" size="lg" drawer>
@if ($inputs)
    <x-slot:buttons>
        <x-button.submit size="sm" label="Send" icon="paper-plane"/>
    </x-slot:buttons>

    <x-form.group cols="2">
        <x-form.email wire:model.defer="inputs.from.email" label="Sender Email"/>
        <x-form.text wire:model.defer="inputs.from.name" label="Sender Name"/>
    </x-form.group>

    <x-form.group>
        <x-form.email wire:model="inputs.to" :options="$emails" multiple/>
        <x-form.email wire:model="inputs.cc" :options="$emails" multiple/>
        <x-form.text wire:model.defer="inputs.subject"/>
        <x-form.textarea wire:model.defer="inputs.body" rows="10"/>
        <x-form.email wire:model="inputs.bcc" label="Send a copy to" multiple/>

        @if ($attach = data_get($inputs, 'attachment'))
            <x-form.field label="Attachment" :value="data_get($attach, 'name')"/>
        @endif
    </x-form.group>
@endif
</x-form>
<div class="w-full">
    <x-page-header title="Website Profile"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text wire:model.defer="settings.company"/>
            <x-form.textarea wire:model.defer="settings.briefs" label="Brief Description"/>
            <x-form.text wire:model.defer="settings.phone"/>
            <x-form.text wire:model.defer="settings.email"/>
            <x-form.textarea wire:model.defer="settings.address"/>
            <x-form.text wire:model.defer="settings.gmap_url" label="Google Map URL"/>
        </x-form.group>

        <x-form.group cols="2">
            <div class="col-span-2">
                <x-form.checkbox wire:model="settings.whatsapp_bubble" label="Whatsapp Bubble"/>
            </div>

            @if (data_get($settings, 'whatsapp_bubble'))
                <x-form.phone wire:model.defer="settings.whatsapp" label="Whatsapp Number"/>
                <x-form.textarea wire:model.defer="settings.whatsapp_text" label="Whatsapp Prefill Text"/>
            @endif
        </x-form.group>
    </x-form>
</div>

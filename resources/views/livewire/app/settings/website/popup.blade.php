<div class="w-full">
    <x-page-header title="Website Pop-Up"/>
    
    <x-form>
        <x-form.group>
            <x-form.richtext wire:model.defer="popup.content" label="Pop-Up Content"/>
        </x-form.group>
        <x-form.group cols="2">
            <x-form.number wire:model.defer="popup.delay" caption="Put 0 to disable pop-up." postfix="miliseconds"/>
        </x-form.group>
    </x-form>
</div>
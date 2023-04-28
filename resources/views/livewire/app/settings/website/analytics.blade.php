<div class="w-full">
    <x-page-header title="Website Analytics"/>
    
    <x-form>
        <x-form.group>
            <x-form.text wire:model.defer="settings.ja_id" label="Jiannius Analytics ID"/>
            <x-form.text wire:model.defer="settings.ga_id" label="Google Analytics ID"/>
            <x-form.text wire:model.defer="settings.gtm_id" label="Google Tag Manager ID"/>
            <x-form.text wire:model.defer="settings.fbp_id" label="Facebook Pixel ID"/>
        </x-form.group>
    </x-form>
</div>
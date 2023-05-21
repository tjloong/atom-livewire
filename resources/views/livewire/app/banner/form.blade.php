<x-form>
    <x-form.group cols="2">
        <x-form.select wire:model="banner.type" :options="data_get($this->options, 'types')"/>
        <x-form.text wire:model.defer="banner.name"/>
        <x-form.text wire:model.defer="banner.url" label="Link URL"/>
        <x-form.slug wire:model.defer="banner.slug"/>
        <x-form.textarea wire:model.defer="banner.description"/>
        
        <x-form.select wire:model="banner.placement" :options="data_get($this->options, 'placements')" multiple
            caption="Leave empty to place in all pages"
        />
        
        <div class="col-span-2">
            <x-form.file wire:model="banner.image_id" accept="image/*"/>
        </div>
    </x-form.group>
    
    <x-form.group>
        <x-form.checkbox wire:model="banner.is_active" label="Active"/>
    </x-form.group>
</x-form>
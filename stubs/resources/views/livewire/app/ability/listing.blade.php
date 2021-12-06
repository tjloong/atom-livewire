<div x-data="{ groups: @entangle('groups') }" wire:ignore class="grid divide-y">
    <template x-for="group in groups" x-bind:key="group.name">
        <div class="py-2 px-4 hover:bg-gray-100">
            <x-modal class="w-96">
                <x-slot name="trigger">
                    <a class="flex">
                        <div class="flex-shrink-0 w-40">
                            <div x-text="group.name" class="font-medium text-gray-800"></div>
                            <div x-show="group.abilities.some(val => (val.overwrote))" class="mt-1">
                                <div class="text-xs font-medium italic text-gray-500">Overwrote</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center space-x-2">
                            <template x-for="(ability, index) in group.abilities" x-bind:key="ability.id">
                                <div class="flex items-center space-x-1">
                                    <div x-show="index > 0" class="mx-2">&bull;</div>

                                    <x-icon x-show="ability.enabled" name="check" class="text-green-500"/>
                                    <x-icon x-show="!ability.enabled" name="x" class="text-red-400"/>
                                    
                                    <div
                                        x-text="ability.name"
                                        x-bind:class="ability.enabled ? 'text-green-500' : 'font-medium text-gray-500'"
                                        class="text-xs capitalize"
                                    ></div>
                                </div>
                            </template>
                        </div>
                    </a>
                </x-slot>

                <x-slot name="title">
                    Update Permissions
                </x-slot>

                <div>
                    <x-input.field>
                        <x-slot name="label">Module</x-slot>
                        <span x-text="group.name"></span>
                    </x-input.field>
                
                    <x-input.field>
                        <x-slot name="label">Permissions</x-slot>
                        <div class="grid space-y-1">
                            <template x-for="ability in group.abilities" x-bind:key="ability.id">
                                <x-input.checkbox x-bind:checked="ability.enabled" x-on:input="$wire.save(ability)">
                                    <span class="capitalize" x-text="ability.name"></span>
                                </x-input.checkbox>
                            </template>
                        </div>
                    </x-input.field>

                    <x-button
                        x-show="group.is_overwrote" 
                        x-on:click="$wire.resetOverwrote(group.abilities.map(val => (val.id))).then(() => close())"
                        color="gray"
                    >
                        Reset
                    </x-button>
                </div>
            </x-modal>
        </div>
    </template>
</div>

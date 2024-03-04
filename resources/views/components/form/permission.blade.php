<x-box>
    <div x-cloak
        x-data="{
            permissions: @entangle($attributes->wire('model')),
            all (allow = true) {
                Object.keys(this.permissions).forEach(module => {
                    Object.keys(this.permissions[module]).forEach(action => {
                        this.permissions[module][action] = allow
                    })
                })
            },
        }"
        x-modelable="permissions"
        class="flex flex-col">
        <template x-for="(actions, module) in permissions">
            <div class="py-2 px-4 grid gap-3 md:grid-cols-12 hover:bg-slate-50 border-b">
                <div class="md:col-span-4 font-medium capitalize" x-text="module.split('-').join(' ')"></div>
                <div class="md:col-span-8 flex items-center gap-2 flex-wrap">
                    <template x-for="(perm, action) in actions">
                        <div
                            x-on:click="permissions[module][action] = !perm"
                            x-bind:class="perm ? 'bg-slate-100' : 'bg-white text-gray-400'"
                            class="flex items-center gap-2 cursor-pointer border py-0.5 px-2 rounded-md text-sm">
                            <div x-show="perm" class="shrink-0">
                                <x-icon name="check" class="text-green-500"/>
                            </div>
                            <div class="grow capitalize" x-text="action.split('-').join(' ')"></div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <div class="flex items-center gap-2 p-3">
            <x-button xs inverted color="green" icon="check" label="app.label.allow-all" x-on:click="all()"/>
            <x-button xs inverted color="red" icon="xmark" label="app.label.forbid-all" x-on:click="all(false)"/>
        </div>
    </div>
</x-box>
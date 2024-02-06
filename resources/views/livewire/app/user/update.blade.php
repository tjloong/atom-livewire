<x-form.drawer id="user-update">
@if ($user)
    @if ($user->exists)
        <x-slot:heading title="{{ $user->name }}" subtitle="{{ $user->email }}"></x-slot:heading>

        <x-slot:buttons
            :trash="!$user->trashed()"
            :restore="$user->trashed()"
            :delete="$user->trashed()"></x-slot:buttons>
    @else
        <x-slot:heading title="Create User"></x-slot:heading>
    @endif

    <div class="-m-4">
        <x-form.group cols="2">
            <div class="col-span-2">
                <x-form.text wire:model.defer="user.name"/>
            </div>
    
            @if ($this->isLoginMethod('username'))
                <x-form.text wire:model.defer="user.username"/>
            @endif
    
            @if ($this->isLoginMethod(['email', 'email-verified']))
                <x-form.email wire:model.defer="user.email" label="Login Email"/>
            @endif
    
            <x-form.password wire:model.defer="inputs.password"/>
    
            <div class="col-span-2">
                <x-form.checkbox wire:model="inputs.is_blocked" label="Blocked"/>
    
                @tier('root')
                    <x-form.checkbox wire:model="inputs.is_root" label="Root"/>
                @endtier
            </div>
        </x-form.group>

        @if ($this->permissions)
            <x-form.field label="Permissions">
                <x-box>
                    <div class="flex flex-col divide-y">
                        @foreach ($this->permissions as $module => $actions)
                            <div class="py-2 px-4 grid gap-3 md:grid-cols-12 hover:bg-slate-50">
                                <div class="md:col-span-4 font-medium">
                                    {{ str()->headline($module) }}
                                </div>
                                <div class="md:col-span-8 flex items-center gap-2 flex-wrap">
                                    @foreach ($actions as $action => $permission)
                                        <div
                                            wire:click="togglePermission(@js($module), @js($action))" 
                                            class="flex items-center gap-2 cursor-pointer border py-0.5 px-2 rounded-md text-sm {{ 
                                                $permission ? 'bg-slate-100' : 'bg-white text-gray-400'
                                            }}">
                                            @if ($permission)
                                                <div class="shrink-0">
                                                    <x-icon name="check" class="text-green-500"/>
                                                </div>
                                            @endif

                                            <div class="grow">
                                                {{ str()->headline($action) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-box>
            </x-form.field>
        @endif
    </div>
@endif
</x-form.drawer>

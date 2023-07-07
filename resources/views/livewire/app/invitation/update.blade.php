<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$this->title" :status="$invitation->status" back>
        <x-button.delete inverted label="Cancel Invitation"
            title="Cancel Invitation"
            message="Are you sure to Cancel this invitation?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6">
        <x-box>
            <div class="flex flex-col divide-y">
                @foreach ([
                    'Email' => $invitation->email,
                    'Invited By' => $invitation->createdBy->name,
                    'Invited Date' => format_date($invitation->created_at),
                    'Expiry Date' => format_date($invitation->expired_at),
                ] as $key => $val)
                    <x-field :label="$key" :value="$val"/>
                @endforeach
            </div>
        </x-box>

        @if ($this->permissions && $invitation->status === 'pending')
            <x-box header="Permissions">
                <div class="flex flex-col divide-y max-h-[450px] overflow-auto">
                    @foreach ($this->permissions as $module => $actions)
                        <div class="p-4 grid gap-4 md:grid-cols-3 hover:bg-slate-100">
                            <div class="px-2 flex items-center gap-2 font-medium">
                                <x-icon name="lock" class="text-gray-400"/> {{ str($module)->headline() }}
                            </div>

                            <div class="md:col-span-2 flex items-center flex-wrap">
                                @foreach ($actions as $action)
                                    @php $permission = implode('.', [$module, $action]) @endphp

                                    <div class="px-2 flex items-center gap-2">
                                        @if (collect(data_get($invitation->data, 'permissions'))->contains($permission)) <x-icon name="circle-check" class="text-green-500"/>
                                        @else <x-icon name="circle-minus" class="text-gray-400"/>
                                        @endif

                                        <x-link wire:click="toggle('{{ $permission }}')" :label="str($action)->headline()"/>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-box>
        @endif
    </div>
</div>
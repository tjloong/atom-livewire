<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$this->title" back/>

    <x-box icon="envelope" :header="$this->header">
        <div class="flex flex-col divide-y">
            <div class="p-4">
                <x-form.text wire:model.debounce.400ms="inputs.search" 
                    label="Search by email or full name" 
                    prefix="icon:search"
                >
                    @if ($search = data_get($inputs, 'search'))
                        <x-slot:button icon="close" wire:click="$set('inputs.search', null)"></x-slot:button>
                    @endif
                </x-form.text>
            </div>

            @if ($this->users && $this->users->count())
                @foreach ($this->users as $user)
                    <div class="py-2 px-4 flex items-center gap-3 hover:bg-slate-100">
                        <div class="shrink-0 flex">
                            <x-icon name="envelope" class="text-gray-400 m-auto"/>
                        </div>

                        <div class="grow font-medium flex items-center gap-2">
                            @if (
                                $this->isEmail 
                                && ($email = data_get($user, 'email'))
                                && ($email === $search)
                            ) 
                                <span>{{ $email }}</span>
                            @elseif ($name = data_get($user, 'name')) <span>{{ $name }}</span> 
                            @endif
                        </div>

                        <div class="shrink-0">
                            <x-button.confirm label="Invite" color="green" icon="plus" size="xs"
                                title="Invite User"
                                message="Are you sure to invite {{ data_get($user, 'email') }}?"
                                callback="invite"
                                :params="data_get($user, 'email')"
                            />
                        </div>
                    </div>
                @endforeach
            @elseif ($search)
                <div class="py-2 px-4 text-gray-400 font-medium">
                    {{ __('Unable to find user') }} {{ $search }}
                </div>
            @endif
        </div>
    </x-box>
</div>
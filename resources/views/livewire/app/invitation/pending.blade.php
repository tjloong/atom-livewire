<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$this->title"/>

    <x-box>
        @if ($this->invitations->count())
            <div class="flex flex-col divide-y">
                @foreach ($this->invitations as $invitation)
                    <div class="py-2 px-4 flex items-center gap-3 flex-wrap hover:bg-slate-100 md:flex-nowrap">
                        <div class="shrink-0 text-gray-500">
                            {{ data_get($invitation, 'date') }}
                        </div>

                        <div class="grow">
                            <span class="font-medium">{{ data_get($invitation, 'created_by') }}</span> 
                            <span class="text-gray-500">{{ __('invited you') }}</span>
                            @if ($tenant = data_get($invitation, 'tenant'))
                                <span class="text-gray-500">{{ __('to join') }}</span> <span class="font-medium">{{ $tenant }}</span>
                            @endif
                        </div>
                        
                        <div class="shrink-0 flex items-center">
                            <x-button.confirm label="Accept" icon="check" size="sm" color="green" inverted
                                title="Accept Invitation"
                                message="Are you sure to accept this invitation?"
                                callback="accept"
                                :params="data_get($invitation, 'id')"
                            />
                            
                            <x-button.confirm label="Decline" icon="xmark" size="sm" color="red" inverted
                                title="Decline Invitation"
                                message="Are you sure to decline this invitation?"
                                callback="decline"
                                :params="data_get($invitation, 'id')"
                                type="error"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state/>
        @endif
    </x-box>
</div>
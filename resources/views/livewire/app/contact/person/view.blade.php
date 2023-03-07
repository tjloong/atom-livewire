<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$person->name" back>
        @can('contact.update')
            <x-button color="gray"
                label="Edit"
                :href="route('app.contact.person.update', [$person->id])"
            />
        @endcan

        @can('contact.delete')
            <x-button.delete inverted
                title="Delete Contact Person"
                message="This will DELETE the contact person. Are you sure?"
            />
        @endcan
    </x-page-header>

    <x-box>
        <div class="flex flex-col divide-y">
            @foreach ($this->fields as $key => $val)
                <x-field :label="$key" :value="$val"/>
            @endforeach
        </div>
    </x-box>
</div>
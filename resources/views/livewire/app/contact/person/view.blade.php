<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$person->name" back>
        @can('contact.update')
            <x-button color="gray"
                label="Edit"
                :href="route('app.contact.person.update', [$contact->id, $person->id])"
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
            @foreach ([
                'Company' => $contact->name,
                'Name' => collect([$person->salutation, $person->name])->filter()->join(' '),
                'Email' => $person->email,
                'Phone' => $person->phone,
                'Designation' => $person->designation,
            ] as $key => $val)
                <x-box.row :label="$key">{{ $val }}</x-box.row>
            @endforeach
        </div>
    </x-box>
</div>
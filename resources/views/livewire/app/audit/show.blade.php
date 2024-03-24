<x-form.drawer wire:close="close()">
@if (optional($audit)->exists)
    <x-slot:heading title="app.label.audit-trail"></x-slot:heading>

    <x-group>
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="app.label.date" :value="format($audit->created_at, 'datetime')"/>
                <x-field label="app.label.user" :value="$audit->user->name ?? '--'"/>
                <x-field label="app.label.event" :badges="$audit->event->badge()"/>
                <x-field label="app.label.type" :value="str($audit->auditable_type)->headline()"/>
                <x-field label="app.label.tag" :tags="$audit->tags"/>
                <x-field label="app.label.user-agent" :value="$audit->getJson('request.user_agent')"/>
            </div>
        </x-box>
    </x-group>

    @if ($audit->old_values)
        <x-group heading="app.label.old-value">
            <x-box>
                <div class="flex flex-col divide-y">
                    @foreach ($audit->old_values as $key => $val)
                        <x-field :label="$key" :value="$val"/>
                    @endforeach
                </div>
            </x-box>
        </x-group>
    @endif

    @if ($audit->new_values)
        <x-group heading="app.label.new-value">
            <x-box>
                <div class="flex flex-col divide-y">
                    @foreach ($audit->new_values as $key => $val)
                        <x-field :label="$key" :value="$val"/>
                    @endforeach
                </div>
            </x-box>
        </x-group>
    @endif
@endif
</x-form.drawer>
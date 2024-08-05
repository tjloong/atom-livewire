<x-drawer wire:close="cleanup">
@if (optional($audit)->exists)
    <x-slot:heading title="app.label.audit-trail"></x-slot:heading>

    <div class="p-5 flex flex-col gap-5">
        <x-fieldset>
            <x-field label="app.label.date" :value="format($audit->created_at, 'datetime')"/>
            <x-field label="app.label.user" :value="$audit->user->name ?? '--'"/>
            <x-field label="app.label.event" :badges="[$audit->event->badge()]"/>
            <x-field label="app.label.type" :value="str($audit->auditable_type)->headline()"/>
            <x-field label="app.label.tag" :tags="$audit->tags"/>
            <x-field label="app.label.user-agent" :value="$audit->getJson('request.user_agent')"/>

            @if ($audit->old_values)
                <x-fieldset group="app.label.old-value"/>
                @foreach ($audit->old_values as $key => $val)
                    <x-field :label="$key">
                        @if (is_array($val)) @json($val)
                        @else {!! $val !!}
                        @endif
                    </x-field>
                @endforeach
            @endif

            @if ($audit->new_values)
                <x-fieldset group="app.label.new-value"/>
                @foreach ($audit->new_values as $key => $val)
                    <x-field :label="$key">
                        @if (is_array($val)) @json($val)
                        @else {!! $val !!}
                        @endif
                    </x-field>
                @endforeach
            @endif
        </x-fieldset>
    </div>
@endif
</x-drawer>
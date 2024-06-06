<div x-data="{
    open (payload) {
        this.$dispatch('show-footprint', payload)
    },
}" class="inline-block">
    {{ $slot }}

    <template x-teleport="body">
        <x-modal id="footprint-modal-{{ str()->random() }}" class="max-w-lg">
            <x-slot:heading title="app.label.footprint" icon="shoe-prints"></x-slot:heading>

            <div
                x-data="{
                    footprint: [],
                    auditable: null,
                }"
                x-on:show-footprint.window="() => {
                    footprint = $event.detail.footprint
                    auditable = $event.detail.auditable
                    if (footprint || auditable) open()
                }"
                class="flex flex-col divide-y">
                <template x-if="!footprint || !footprint.length" hidden>
                    <x-no-result title="app.label.no-footprint"/>
                </template>

                <template x-for="item in footprint" hidden>
                    <div x-text="item" class="py-2 px-4"></div>
                </template>

                @if (has_route('app.audit'))
                <template x-if="auditable" hidden>
                    <x-anchor label="app.label.audit-trail" align="center" class="py-2" x-on:click.stop="() => {
                        href(`{{ route('app.audit') }}?filters[auditable_id]=${auditable.id}&filters[auditable_type]=${auditable.type}`)
                    }"/>
                </template>
                @endif
            </div>
        </x-modal>
    </template>
</div>

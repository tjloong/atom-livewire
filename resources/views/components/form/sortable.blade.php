<{{ $attributes->get('el', 'div') }}
    x-data="{
        config: @js($attributes->get('config')),
        sortable: null,
        input () {
            const sorted = Array.from(this.$el.children)
                .map(child => (child.getAttribute('data-sortable-id')))

            this.$dispatch('sorted', sorted)
        },
    }"
    x-init="this.sortable = new Sortable($el, { ...config, onSort: () => input() })"
    {{ $attributes->except('config') }}
>
    {{ $slot }}
</{{ $attributes->get('el', 'div') }}>

<{{ $el }}
    x-data="{
        config: @js($attributes->get('config')),
        sortable: null,
        input () {
            const sorted = Array.from(this.$el.children)
                .map(child => (child.getAttribute('data-sortable-id')))

            this.$dispatch('sort', sorted)
        },
    }"
    x-init="this.sortable = new Sortable($el, { ...config, onSort: () => input() })"
    {{ $attributes->except('config') }}
>
    {{ $slot }}
</{{ $el }}>

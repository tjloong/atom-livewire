@if ($slot->isNotEmpty())
    @props([
        'config' => $attributes->get('config'),
    ])

    <div x-data="{
        config: @js($config),
        init () {
            const hasHandle = Array.from(this.$el.children).some((child) => (
                child.querySelectorAll('.handle').length > 0
            ))

            this.sortable = new Sortable($el, { 
                handle: hasHandle ? '.handle' : false,
                ...this.config, 
                onSort: () => {
                    const sorted = Array.from(this.$el.children)
                        .map(child => (child.getAttribute('data-sortable-id')))

                    this.$dispatch('sorted', sorted)
                },
            })
        },
    }" {{ $attributes->except('config') }}>
        {{ $slot }}
    </div>
@endif
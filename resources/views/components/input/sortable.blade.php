@if (!$attributes->has('script'))
<{{ $el }}
    x-data="sortableInput(
        @if ($attributes->wire('model')->value()) $wire.get('{{ $attributes->wire('model')->value() }}'),
        @elseif ($value) @js($value),
        @else null,
        @endif
        @js($config)
    )"
    wire:ignore
    {{ $attributes }}
>
    {{ $slot }}
</{{ $el }}>
@endif

@if ($attributes->has('script') || !$attributes->has('no-script'))
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sortableInput', (value = null, config = null) => ({
            value,
            sortable: null,

            get children () {
                return Array.from(this.$el.children)
                    .filter(child => (!child.tagName.includes('TEMPLATE')))
            },

            init () {
                ScriptLoader.load('https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js').then(() => {
                    this.setIdentifier()
                    this.sortable = new Sortable(this.$el, { ...config, onSort: () => this.input() })
                })
            },

            setIdentifier () {
                if (!this.value) return

                // add identifier to each value
                this.value = this.value.map(val => ({ ...val, sortableId: random() }))

                // map the item elements to value
                this.children.forEach((child, i) => child.setAttribute('data-sortable-id', this.value[i].sortableId))
            },

            input () {
                if (!this.value) this.$dispatch('input')
                else {
                    const sorted = []

                    this.children.forEach(child => {
                        const id = child.getAttribute('data-sortable-id')
                        const value = this.value.find(val => (val.sortableId === id))
                        sorted.push(value)
                    })

                    this.$dispatch('input', sorted)
                }
            },
        }))
    })
</script>
@endif

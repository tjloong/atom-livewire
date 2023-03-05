<div
    x-data="{
        uid: @js(component_id($attributes, 'tab')),
        value: @entangle($attributes->wire('model')),
        goTab (e) {
            if (e.uid !== this.uid) return
            
            if (e.href) {
                if (e.target === '_blank') window.open(e.href)
                else window.location = e.href
            }
            else this.value = e.name
        },
    }"
    x-on:select-tab.window="goTab($event.detail)"
    class="inline-flex items-center flex-wrap gap-4"
    {{ $attributes }}
>
    {{ $slot }}
</div>

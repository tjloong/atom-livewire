@props([
    'to' => $attributes->get('to'),
    'id' => component_id($attributes, 'countdown'),
])

<div x-data="{
    to: @js(strtotime($to)),
    init () {
        this.$nextTick(() => {
            new FlipDown(this.to, @js($id)).start();
        })
    }
}">
    <div id="{{ $id }}" class="flipdown"></div>
</div>
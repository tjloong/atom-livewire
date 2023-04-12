@props([
    'content' => settings('popup.content'),
    'delay' => settings('popup.delay'),
])

@if (
    !empty($content) 
    && is_numeric($delay) 
    && $delay > 0 
    && !str(request()->headers->get('referer'))->is(config('app.url').'*')
)
    <x-modal>
        <div class="p-4 prose max-w-none">
            {!! $content !!}
        </div>

        <div x-data x-init="setTimeout(() => $dispatch('open'), @js($delay))"></div>
    </x-modal>
@endif
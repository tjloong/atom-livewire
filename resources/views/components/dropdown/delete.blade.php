@php    
$label = $attributes->get('label') ?? 'app.label.delete';
$count = $attributes->get('count', 1);
$params = $attributes->get('params');
$reload = $attributes->get('reload', false);
$trash = $attributes->get('trash', false);
$callback = $attributes->get('callback', ($trash ? 'trash' : 'delete'));
$title = $attributes->get('title') ?? ($trash ? 'app.alert.trash.title' : 'app.alert.delete.title');
$message = $attributes->get('message') ?? ($trash ? 'app.alert.trash.message' : 'app.alert.delete.message');
$delegate = $attributes->has('x-on:confirm-delete');
$except = ['icon', 'label', 'count', 'title', 'message', 'callback', 'params', 'reload', 'delegate', 'x-on:click'];
@endphp

<div
    x-on:click.stop="$dispatch('confirm', {
        title: '{!!tr($title)!!}',
        message: '{!!tr($message, $count)!!}',
        type: 'error',
        onConfirmed: () => {{Js::from($delegate)}}
            ? $dispatch('confirm-delete')
            : $wire.call({{Js::from($callback)}}, {{Js::from($params)}}).then(() => {
                {{Js::from($reload)}} && location.reload()
            }),
    })"
    {{ $attributes->class([
        'flex items-center gap-3 cursor-pointer hover:bg-slate-50',
        $attributes->get('class', 'py-2 px-4 text-red-500 font-medium'),
    ])->except($except) }}>
    <div class="shrink-0 flex w-4">
        <x-icon name="delete" class="m-auto"/>
    </div>

    <div class="grow">
        {!! tr($label) !!}
    </div>

    @if ($count > 1)
        <div class="shrink-0 flex">
            <div class="w-5 h-5 m-auto bg-red-100 rounded-full flex items-center justify-center text-sm">
                {{ $count }}
            </div>
        </div>
    @endif
</div>

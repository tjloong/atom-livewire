@php
$message = $attributes->get('message');
$name = $attributes->get('name') ?? get($message, 'user.name');
$files = collect($attributes->get('files') ?? get($message, 'files'));
$content = $attributes->get('content') ?? get($message, 'content');
$timestamp = $attributes->get('timestamp') ?? carbon(get($message, 'created_at'))->recent();
$isSelf = $attributes->get('is-self') ?? (get($message, 'user_id') && get($message, 'user_id') === user('id'));
$isLog = $attributes->get('is-log') ?? get($message, 'is_log');
$isNew = $attributes->get('is-new') ?? get($message, 'is_new');
$deleteable = $attributes->get('deleteable') ?? $isSelf;

$attrs = $attributes
    ->except(['message', 'name', 'files', 'content', 'timestamp', 'is-self', 'is-new', 'is-log', 'deleteable']);
@endphp

<div
    x-init="$nextTick(() => {
        Array.from($el.querySelectorAll('.mention'))
            .forEach(mention => mention.addEventListener('click', () => $dispatch('click-mention', mention)))
    })"
    class="group flex flex-col gap-2 {{ $isSelf ? 'items-end' : 'items-start' }}"
    {{ $attrs }}>
    <div class="flex items-center gap-3">
        @if ($deleteable)
            <div
                x-tooltip="{{ js(t('delete')) }}"
                x-on:click="() => Atom.confirm({ type: 'delete' })
                    .then(() => $dispatch('delete-message', {{ js($message) }}))"
                class="shrink-0 text-muted-more cursor-pointer items-center justify-center hidden group-hover:flex">
                <atom:icon delete/>
            </div>
        @endif

        <div class="text-sm text-muted">
            <span class="font-medium">@e($name)</span> - @e($timestamp)
        </div>
    </div>

    @if ($isLog)
        <span class="max-w-xl bg-zinc-200 border border-zinc-300 rounded-md text-sm py-0.5 px-2 text-zinc-500 shadow-sm text-center">
            @ee($content)
        </span>
    @else
        <div class="relative rounded-xl py-3 px-4 shadow-sm border max-w-full md:max-w-xl w-max {{
            $isSelf
            ? 'rounded-br-none bg-zinc-200 border-zinc-300'
            : 'rounded-bl-none bg-white border-zinc-200'
        }}">
            @if ($isNew)
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></div>
            @endif

            <div class="editor-content editor-chat-content">
                @ee($content)
            </div>

            @if ($files->count())
                <div class="py-2 flex items-center gap-3">
                    @foreach ($files as $file)
                        <div
                            x-on:click="Atom.lightbox({
                                gallery: {{ js($files) }},
                                slide: {{ js($file) }},
                            })"
                            class="w-14 cursor-pointer space-y-2">
                            <atom:file :file="$file" variant="card"/>
                            <div class="text-xs text-muted truncate">@e($file->name)</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>

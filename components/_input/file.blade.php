@aware(['invalid'])

@php
$classes = $attributes->classes()
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-zinc-100')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add($invalid ? 'border-red-400' : 'group-has-[[data-atom-error]]/field:border-red-400')
    ;

$attrs = $attributes
    ->class($classes)
    ;
@endphp

<atom:uploader tabindex="0" :attributes="$attrs">
    @if ($slot->isNotEmpty())
        <div class="bg-white p-1 rounded-t-lg border-b border-zinc-200">
            {{ $slot }}
        </div>
    @endif

    <div class="p-4 rounded space-y-2">
        <div class="flex items-center gap-3 font-medium">
            <div
                data-atom-uploader-trigger
                class="underline decoration-dotted flex items-center gap-2 cursor-pointer">
                <atom:icon upload class="text-muted-more"/> @t('browse-device')
            </div>

            @if ($attributes->get('library', false))
                <span class="text-muted-more"> / </span>
                <div
                    x-on:click.stop="Atom.modal('app.file.library').show()"
                    class="underline decoration-dotted flex items-center gap-2 lowercase cursor-pointer">
                    @t('or-browse-library')
                </div>
            @endif
        </div>

        <div class="font-medium text-muted">
            @t('or-drop-paste-to-upload')
        </div>
    </div>
</atom:uploader>

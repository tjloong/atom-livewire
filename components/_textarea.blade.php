@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$invalid = $attributes->get('invalid');
$autoresize = $attributes->get('autoresize');
$placeholder = $attributes->get('placeholder');
$transparent = $attributes->get('transparent');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$classes = $attributes->classes()
    ->add('w-full text-zinc-700')
    ->add($transparent
        ? 'resize-none'
        : 'py-2 px-3 border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add($invalid && !$transparent
        ? 'border-red-400'
        : 'group-has-[[data-atom-error]]/field:border-red-400')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add('disabled:resize-none read-only:resize-none')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'rows' => $transparent ? 1 : 3,
        'x-autosize' => $autoresize || $transparent,
        'x-init' => $autoresize || $transparent ? '$autosize()' : null,
        'required' => $required,
    ])
    ->except(['label', 'caption', 'field', 'error', 'placeholder', 'invalid', 'transparent'])
    ;
@endphp

@if ($label || $caption)
    <atom:_field>
        @if ($label)
            <atom:_label>
                <div class="inline-flex items-center justify-center gap-2">
                    @t($label)
                    @if ($required)
                        <atom:icon asterisk size="12" class="text-red-500 shrink-0"/>
                    @endif
                </div>
            </atom:_label>
        @endif

        <atom:_textarea :attributes="$attributes->except(['label', 'caption'])"/>
        <atom:_error>@t($error)</atom:_error>
        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
@else
    <textarea
        {{ $attrs }}
        placeholder="{!! t($placeholder) !!}"
        data-atom-textarea>
    </textarea>
@endif

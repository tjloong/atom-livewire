@php
$classes = $attributes->classes()
    ->add('group/field block space-y-2')
    // ->add('[&>[data-atom-label]+[data-atom-input]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-input]+[data-atom-caption]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-input]+[data-atom-error]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-input]+[data-atom-error]+[data-atom-caption]]:mt-1.5')
    // ->add('[&>[data-atom-label]+[data-atom-textarea]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-textarea]+[data-atom-caption]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-textarea]+[data-atom-error]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-textarea]+[data-atom-error]+[data-atom-caption]]:mt-1.5')
    // ->add('[&>[data-atom-label]+[data-atom-caption]]:mt-2')
    // ->add('[&>[data-atom-label]+[data-atom-error]]:mt-2')
    // ->add('[&>[data-atom-label]]:mb-3')
    // ->add('[&>[data-atom-input]+[data-atom-caption]]:mt-3')
    // ->add('[&>[data-atom-input]+[data-atom-error]+[data-atom-caption]]:mt-2')
    // ->add('[&>[data-atom-input]+[data-atom-error]]:mt-3')
    // ->add('[&>*:not([data-atom-label])+[data-atom-caption]]:mt-3')
    ;

$attrs = $attributes->class($classes);
@endphp

<div {{ $attrs }} data-atom-field>
    {{ $slot }}
</div>
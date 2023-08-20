<x-form.field :label="false" {{ $attributes }}>
    <div class="{{ component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null }}">
        <x-form.checkbox 
            :label="$attributes->get('label', __('atom::form.checkbox.privacy'))"
            {{ $attributes->except('label') }}>
            @if (has_table('pages'))
                <x-slot:small>
                    @if (
                        $links = model('page')->whereIn('name', ['Terms', 'Privacy'])->get()
                            ->mapWithKeys(fn($page) => [$page->title => '/'.$page->slug])
                            ->all()
                    )
                        <div class="flex flex-col text-sm md:flex-row md:flex-wrap md:items-center md:gap-2">
                            @foreach ($links as $label => $href)
                                <a href="{{ $href }}" target="_blank" class="shrink-0">
                                    {{ __($label) }}
                                </a>
                                @if ($label !== array_key_last($links)) <span class="hidden md:block">|</span> @endif
                            @endforeach
                        </div>
                    @endif
                </x-slot:small>
            @endif 
        </x-form.checkbox>
    </div>
</x-form.field>

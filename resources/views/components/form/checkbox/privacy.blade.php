<x-form.field :label="false" {{ $attributes }}>
    <div class="{{ component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null }}">
        <x-form.checkbox 
            :label="$attributes->get('label', 'common.label.agree-privacy')"
            {{ $attributes->except('label') }}>
            @if (has_table('pages'))
                <x-slot:caption>
                    @if ($links = model('page')->whereIn('name', ['Terms', 'Privacy'])->get()
                        ->mapWithKeys(fn($page) => [$page->title => '/'.$page->slug])
                        ->all()
                    )
                        <div class="flex flex-col text-sm md:flex-row md:flex-wrap md:items-center">
                            @foreach ($links as $label => $href)
                                <div class="shrink-0">
                                    <x-link :href="$href" :label="$label" target="_blank"/>
                                </div>
                                @if ($label !== array_key_last($links)) <span class="hidden md:block md:px-2">|</span> @endif
                            @endforeach
                        </div>
                    @endif
                </x-slot:caption>
            @endif 
        </x-form.checkbox>
    </div>
</x-form.field>

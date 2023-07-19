<x-form.field :label="false" {{ $attributes }}>
    <div class="{{ component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null }}">
        <x-form.checkbox {{ $attributes->except('label') }}>
            <div class="flex flex-col py-0.5 gap-1 text-gray-500">
                {{ __($attributes->get('label', 'I have read and agreed to the website\'s Terms of Use and Privacy Policy.')) }}
        
                @if (has_table('pages'))
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
                @endif 
            </div>
        </x-form.checkbox>
    </div>
</x-form.field>

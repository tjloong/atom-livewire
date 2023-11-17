<main class="{{ count($this->tabs) > 1 ? 'max-w-screen-xl' : 'max-w-screen-lg' }} mx-auto">
    <div class="w-full flex flex-col gap-10">
        <nav class="flex flex-wrap items-center justify-between gap-2">
            <x-logo class="w-40"/>
            <x-link icon="back" label="I'll do this later" wire:click="close"/>
        </nav>

        <div>
            <h1 class="text-xl font-bold">{{ tr('onboarding.title') }}</h1>
            <div class="text-gray-500 font-medium">{{ tr('onboarding.subtitle') }}</div>
        </div>

        <div class="flex flex-col gap-6 md:flex-row">
            @if (count($this->tabs) > 1)
                <div class="md:w-1/4">
                    <x-sidenav wire:model="tab">
                        @foreach ($this->tabs as $item)
                            @if ($group = data_get($item, 'group'))
                                <x-sidenav.group :label="$group"/>
                            @else
                                <x-sidenav.item 
                                    :value="data_get($item, 'slug')"
                                    :label="data_get($item, 'label')"
                                    :count="data_get($item, 'count')"
                                    :href="route('app.onboarding', [
                                        'tab' => data_get($item, 'slug'),
                                        'redirect' => $redirect,
                                    ])"
                                    :disabled="$tab !== data_get($item, 'slug') 
                                        && !in_array(data_get($item, 'slug'), session('onboarding', []))"
                                >
                                    <x-slot:icon>
                                        <div class="flex items-center justify-center">
                                            @if (in_array(data_get($item, 'slug'), session('onboarding', []))) 
                                                <x-icon name="circle-check" class="text-green-500"/>
                                            @else 
                                                <x-icon name="circle-dot" class="text-gray-400"/>
                                            @endif
                                        </div>
                                    </x-slot:icon>
                                </x-sidenav.item>
                            @endif
                        @endforeach
                    </x-sidenav>
                </div>
            @endif
        
            <div class="{{ count($this->tabs) > 1 ? 'md:w-3/4' : 'w-full' }}">
                @if ($com = data_get(collect($this->tabs)->firstWhere('slug', $tab), 'livewire'))
                    @if (is_string($com)) 
                        @livewire($com, ['onboarding' => true], key($tab))
                    @else 
                        @livewire(data_get($com, 'name'), array_merge(
                            ['onboarding' => true],
                            data_get($com, 'data'),
                        ),key($tab))
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>

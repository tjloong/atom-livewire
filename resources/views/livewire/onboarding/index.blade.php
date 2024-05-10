<main class="{{ count($this->tabs) > 1 ? 'max-w-screen-xl' : 'max-w-screen-lg' }} mx-auto">
    <div class="w-full flex flex-col gap-10">
        <nav class="flex flex-wrap items-center justify-between gap-2">
            <x-logo class="w-32 h-20"/>
            <x-anchor icon="back" label="app.onboarding.ill-do-this-later" :href="user()->home()"/>
        </nav>

        <div>
            <h1 class="text-xl font-bold">{{ tr('app.onboarding.please-spend-a-minute') }}</h1>
            <div class="text-gray-500 font-medium">{{ tr('app.onboarding.quickly-setup-your-account') }}</div>
        </div>

        <div class="flex flex-col items-center justify-center gap-6 md:flex-row">
            @if (count($this->tabs) > 1)
                <div class="md:w-1/4">
                    <div class="flex flex-col">
                        @foreach ($this->tabs as $i => $item)
                            <div
                                x-data="{ 
                                    selected: @js($tab === get($item, 'slug')),
                                    disabled: @js($tab !== get($item, 'slug') && $i > session('onboarding')),
                                }"
                                x-bind:class="{
                                    'bg-gray-200 font-semibold': selected,
                                    'font-medium opacity-30 cursor-not-allowed': disabled,
                                    'hover:bg-gray-100 font-medium text-gray-600 cursor-pointer': !selected && !disabled
                                }"
                                x-on:click="!selected && !disabled && $wire.set('tab', @js(get($item, 'slug')))"
                                class="py-2 px-4 flex items-center gap-3 rounded-md">
                                <div class="shrink-0">
                                    @if ($i < session('onboarding')) <x-icon name="circle-check" class="text-green-500"/>
                                    @elseif ($i === session('onboarding')) <x-icon name="circle-dot" class="text-blue-500"/>
                                    @endif
                                </div>
                                <div class="grow">
                                    {{ get($item, 'label') }}
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                </div>
            @endif
        
            <div class="{{ count($this->tabs) > 1 ? 'md:w-3/4' : 'w-full' }}">
                @if ($path = get($this->selectedTab, 'path'))
                    @livewire($path, array_merge(
                        ['onboarding' => true],
                        get($this->selectedTab, 'params', []),
                    ), key($tab))
                @else
                    @livewire('onboarding.'.$tab, key($tab))
                @endif
            </div>
        </div>
    </div>
</main>

<main class="{{ count($this->tabs) > 1 ? 'max-w-screen-xl' : 'max-w-screen-lg' }} mx-auto">
    <div class="w-full flex flex-col gap-10">
        <nav class="flex flex-wrap items-center justify-between gap-2">
            <x-logo class="w-32 h-20"/>
            <x-link icon="back" label="I'll do this later" :href="user()->home()"/>
        </nav>

        <div>
            <h1 class="text-xl font-bold">{{ tr('onboarding.title') }}</h1>
            <div class="text-gray-500 font-medium">{{ tr('onboarding.subtitle') }}</div>
        </div>

        <div class="flex flex-col gap-6 md:flex-row">
            @if (count($this->tabs) > 1)
                <div class="md:w-1/4">
                    <div class="flex flex-col">
                        @foreach ($this->tabs as $i => $item)
                            <div
                                x-data="{ 
                                    selected: @js($tab === data_get($item, 'slug')),
                                    disabled: @js($tab !== data_get($item, 'slug') && $i > session('onboarding')),
                                }"
                                x-bind:class="{
                                    'bg-gray-200 font-semibold': selected,
                                    'font-medium opacity-30 cursor-not-allowed': disabled,
                                    'hover:bg-gray-100 font-medium text-gray-600 cursor-pointer': !selected && !disabled
                                }"
                                x-on:click="!selected && !disabled && $wire.set('tab', @js(data_get($item, 'slug')))"
                                class="py-2 px-4 flex items-center gap-3 rounded-md">
                                <div class="shrink-0">
                                    @if ($i < session('onboarding')) <x-icon name="circle-check" class="text-green-500"/>
                                    @elseif ($i === session('onboarding')) <x-icon name="circle-dot" class="text-blue-500"/>
                                    @endif
                                </div>
                                <div class="grow">
                                    {{ data_get($item, 'label') }}
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                </div>
            @endif
        
            <div class="{{ count($this->tabs) > 1 ? 'md:w-3/4' : 'w-full' }}">
                @if ($path = data_get($this->selectedTab, 'path'))
                    @livewire($path, array_merge(
                        ['isOnboarding' => true],
                        data_get($this->selectedTab, 'params', []),
                    ), key($tab))
                @else
                    @livewire('app.onboarding.'.$tab, key($tab))
                @endif
            </div>
        </div>
    </div>
</main>

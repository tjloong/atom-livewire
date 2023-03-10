<div class="w-full flex flex-col gap-10">
    <nav class="flex flex-wrap items-center justify-between gap-2">
        <x-logo class="w-40"/>
        @if (!$this->isOnboarded) <x-button inverted icon="arrow-left" label="I'll do this later" wire:click="later"/> @endif
    </nav>

    @if (user()->status !== 'new')
        <div class="max-w-screen-lg mx-auto flex flex-col gap-10">
            <div class="flex flex-col gap-1">
                <div class="text-3xl font-bold">
                    {{ __('You account setup is completed. Thank you for signing up with us.') }}
                </div>
            
                <div class="text-gray-500 text-lg font-medium">
                    {{ __('We are so excited to have you as our newest friend!') }}
                </div>
            </div>
        
            <div>
                <x-button size="md" icon="house"
                    label="Back to Home"
                    :href="$redirect ?? optional(user())->home() ?? '/'"
                />
            </div>
        </div>
    @else
        <div>
            <h1 class="text-xl font-bold">
                {{ __('Please spend a minute to complete the following') }}
            </h1>
            <div class="text-gray-500 font-medium">
                {{ __('This will help us quickly setup your account') }}
            </div>
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
                                    :name="data_get($item, 'slug')"
                                    :label="data_get($item, 'label')"
                                    :count="data_get($item, 'count')"
                                    :href="route('app.onboarding', [
                                        'tab' => data_get($item, 'slug'),
                                        'redirect' => $redirect,
                                    ])"
                                >
                                    <x-slot:icon>
                                        <div class="flex items-center justify-center">
                                            @if (in_array(data_get($item, 'slug'), session('onboarding', []))) 
                                                <x-icon name="circle-check" class="text-green-500" size="18"/>
                                            @else 
                                                <x-icon name="circle-dot" class="text-gray-400" size="18"/>
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
                    @livewire(lw($com), ['onboarding' => true], key($tab))
                @endif
            </div>
        </div>
    @endif
</div>

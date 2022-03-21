<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Site Settings"/>
    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @foreach ($tabs as $item)
                    @if ($item->group)
                        <x-sidenav :group="$item->group">
                            @foreach ($item->tabs as $val)
                                <x-sidenav item :href="route('app.site-settings', [$val->slug])">
                                    {{ $val->label ?? str($val->slug)->headline() }}
                                </x-sidenav>
                            @endforeach
                        </x-sidenav>
                    @else
                        <x-sidenav item :href="route('app.site-settings', [$item->slug])">
                            {{ $item->label ?? str($item->slug)->headline() }}
                        </x-sidenav>
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <div class="grid gap-6">
                @if ($component = livewire_name('app/site-settings/'.$tab))
                    @livewire($component, key($tab))
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$user->name" back>
        <div class="flex items-center gap-2">
            @can('user.'.$user->tier.'.block')
                @livewire(lw('app.user.update.block'), compact('user'), key('block'))
            @endcan

            @can('user.'.$user->tier.'.delete')
                @livewire(lw('app.user.update.delete'), compact('user'), key('delete'))
            @endcan
        </div>
    </x-page-header>

    @livewire(lw('app.user.form'), compact('user'), key('form'))
</div>